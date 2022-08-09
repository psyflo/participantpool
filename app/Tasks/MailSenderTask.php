<?php

namespace App\Tasks;

use App\Models\MailingParticipant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use stdClass;
use Twig\Environment;
use Twig\Loader\ArrayLoader;
use Twig\Markup;
use Twig\TwigFunction;

class MailSenderTask
{
    public function __invoke()
    {
        Log::info('Running mail sender task');

        try
        {
            $sql = <<<EOT
            SELECT mp.id, m.subject, m.content, p.id AS 'participantid', p.name, p.firstname, p.email, p.language
            FROM mailings_participants mp
            INNER JOIN mailings m ON m.id = mp.mailing_id
            INNER JOIN participants p ON p.id = mp.participant_id
            WHERE m.state = :state AND mp.mail_sent IS NULL
            ORDER BY mp.id ASC
            LIMIT 10;
EOT;

            $items = DB::select($sql, ['state' => 1]);

            /*
             * Send mailing
             */
            foreach ($items as $item)
            {
                $mailingParticipant = MailingParticipant::find($item->id);

                if ($mailingParticipant)
                {
                    try
                    {
                        Log::info(sprintf('Sending email to "%s" with address "%s"', $item->name, $item->email));

                        $loader = new ArrayLoader(['content.default' => $item->content]);
                        $twig = new Environment($loader);

                        /*
                         * Define user update link function extension
                         */
                        $functionUpdateLink = new TwigFunction('updatelink', function($text = null) use($item) {

                            /*
                             * Determine locale for participant
                             */
                            $locale = $item->language === 'DE' ? 'de' : config('app.fallback_locale');

                            /*
                             * Get link expiration days
                             */
                            $expiration = intval(setting('participantpool.updatelink_days'));

                            /*
                             * Build update link to participant
                             */
                            $link = URL::temporarySignedRoute('index.register.update', now()->addMinutes($expiration * 1440), ['id' => $item->participantid, 'locale' => $locale]);

                            /*
                             * Return link as HTML without need of RAW filter
                             */
                            return new Markup(sprintf('<a href="%s">%s</a>', $link, $text ?? $link), 'UTF-8');
                        });

                        $twig->addFunction($functionUpdateLink);

                        /*
                         * Render content
                         */
                        $content = $twig->render('content.default', ['name' => $item->name, 'firstname' => $item->firstname, 'email' => $item->email]);

                        /*
                         * If possible use mailing owner as email sender
                         */
                        if ($mailingParticipant->mailing !== null && $mailingParticipant->mailing->owner !== null)
                        {
                            $sender = $mailingParticipant->mailing->owner;
                        }
                        else
                        {
                            $sender = (object) ['email' => config('mail.from.address'), 'name' => config('mail.from.name')];
                        }

                        /*
                         * Send email
                         */
                        Mail::html($content, function ($message) use($item, $sender) {

                            $message->from($sender->email, $sender->name)->subject($item->subject ?? $item->name)->to($item->email);
                        });

                        $mailingParticipant->mail_sent = now();
                        $mailingParticipant->save();
                    }
                    catch(\Exception $ex)
                    {
                        /*
                         * Do not throw execption to continue sending
                         */
                        // throw $ex;
                        Log::error(sprintf('Failed to send email with error: %s', $ex->getMessage()));
                    }
                }
            }
        }
        finally
        {
        }
    }
}
