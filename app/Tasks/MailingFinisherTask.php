<?php

namespace App\Tasks;

use App\Models\Mailing;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MailingFinisherTask
{
    public function __invoke()
    {
        Log::info('Running mailing finisher task');

        try
        {
            /*
             * Only finish mailings which are currently in RUNNING state and not have unset mails
             */
            $sql = <<<EOT
            SELECT id, state
            FROM (SELECT id, state, (SELECT COUNT(id) FROM mailings_participants WHERE mailing_id = mailings.id AND mail_sent IS NULL) AS 'mails_unsent' FROM mailings) AS T
            WHERE state = :state AND mails_unsent = 0;
EOT;

            $items = DB::select($sql, ['state' => Mailing::RUNNING]);

            /*
             * Finished mailings
             */
            foreach ($items as $item)
            {
                Log::info(sprintf('Finish mailing with id: %s', $item->id));

                $mailing = Mailing::find($item->id);

                if ($mailing !== null)
                {
                    try
                    {
                        $mailing->state = Mailing::FINISHED;
                        $mailing->save();
                    }
                    catch(\Exception $ex)
                    {
                        throw $ex;
                    }
                }
            }
        }
        finally
        {
        }
    }
}
