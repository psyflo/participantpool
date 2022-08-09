<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\JsonException;
use App\Mail\ParticipantUpdateRegistration;
use App\Models\Participant;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Silber\Bouncer\BouncerFacade;

class ParticipantController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function all(Request $request)
    {
        try
        {
            /*
             * Get data chunks range
             */
            $skip = intval($request->get('skip', 0));
            $take = intval($request->get('take', 0));

            /*
             * Fix take size when zero or ommitted
             */
            $take = $take === 0 ? Participant::count() : $take;

            /*
             * Check authorization
             */
            $authorized = BouncerFacade::can('read', Participant::class);

            if ($authorized === false)
            {
                throw new JsonException(__('Not authorized.'));
            }

            /*
             * Read data
             */
            $data = Participant::skip($skip)->take($take)->get();

            return response()->json($data);
        }
        catch(JsonException $jsonEx)
        {
            return response()->json($jsonEx, 500);
        }
        catch (Exception $ex)
        {
            return response()->json($this->handleException($ex, 'Failed to read participants.'), 500);
        }
    }

    public function edit(int $id, Request $request)
    {
        try
        {
            /*
             * Find participant
             */
            $participant = Participant::with(['studies', 'mailings', 'mailings.study'])->find($id);

            if ($participant === null)
            {
                throw new JsonException(__('Participant not found.'));
            }

            /*
             * Check authorization
             */
            $authorized = BouncerFacade::can('read', $participant);

            if ($authorized === false)
            {
                throw new JsonException(__('Not authorized.'));
            }

            return response()->json($participant);
        }
        catch(JsonException $jsonEx)
        {
            return response()->json($jsonEx, 500);
        }
        catch (Exception $ex)
        {
            return response()->json($this->handleException($ex, 'Failed to edit participant.'), 500);
        }
    }

    public function save(int $id, Request $request)
    {
        try
        {
            /*
             * Find participant
             */
            $participant = Participant::with(['studies', 'mailings', 'mailings.study'])->find($id);

            if ($participant === null)
            {
                throw new JsonException(__('Participant not found.'));
            }

            /*
             * Check authorization
             */
            $authorized = BouncerFacade::can('update', $participant);

            if ($authorized === false)
            {
                throw new JsonException(__('Not authorized.'));
            }

            /*
             * Get input data
             */
            $input = json_decode($request->getContent());

            /*
             * Update and save participant
             */
            if (isset($input->data))
            {
                $participant->name = $input->data->name;
                $participant->firstname = $input->data->firstname ?? null;
                $participant->email = $input->data->email;
                $participant->gender = $input->data->gender ?? null;
                $participant->birthdate = $input->data->birthdate ?? null;
                $participant->location = $input->data->location ?? null;
                $participant->education = $input->data->education ?? null;
                $participant->language = $input->data->language ?? null;
                $participant->survey_languages = $input->data->survey_languages ?? null;
                $participant->study_interest = $input->data->study_interest ?? null;

                /*
                 * Only save topic if the right education is selected
                 */
                if ($participant->education === 'UNI' || $participant->education === 'FH')
                {
                    $participant->education_topic = $input->data->education_topic;
                }
                else
                {
                    $participant->education_topic = null;
                }

                /*
                 * Sync studies, needs to be an array with only study identifier
                 */
                $participant->studies()->sync(collect($input->data->studies)->pluck('id')->toArray());

                /*
                 * Be sure updated is always updated, it is important in case of relations
                 */
                $participant->updated_at = now();

                /*
                 * Save and refresh
                 */
                $participant->save();
                $participant->refresh();
            }

            return response()->json($participant);
        }
        catch(JsonException $jsonEx)
        {
            return response()->json($jsonEx, 500);
        }
        catch(Exception $ex)
        {
            return response()->json($this->handleException($ex, 'Failed to save participant.'), 500);
        }
    }

    public function create(Request $request)
    {
        try
        {
            /*
             * Check authorization
             */
            $authorized = BouncerFacade::can('create', Participant::class);

            if ($authorized === false)
            {
                throw new JsonException(__('Not authorized.'));
            }

            /*
             * Get input data
             */
            $input = json_decode($request->getContent());

            /*
             * Create and save participant
             */
            if (isset($input->data))
            {
                $participant = new Participant();

                $participant->name = $input->data->name;
                $participant->firstname = $input->data->firstname ?? null;
                $participant->email = $input->data->email;
                $participant->gender = $input->data->gender ?? null;
                $participant->birthdate = $input->data->birthdate ?? null;
                $participant->location = $input->data->location ?? null;
                $participant->education = $input->data->education ?? null;

                if ($participant->education === 'UNI' || $participant->education === 'FH')
                {
                    $participant->education_topic = $input->data->education_topic;
                }
                else
                {
                    $participant->education_topic = null;
                }

                $participant->language = $input->data->language ?? null;
                $participant->survey_languages = $input->data->survey_languages ?? null;
                $participant->study_interest = $input->data->study_interest ?? null;

                $participant->save();
            }

            return response()->json($participant);
        }
        catch(JsonException $jsonEx)
        {
            return response()->json($jsonEx, 500);
        }
        catch(Exception $ex)
        {
            return response()->json($this->handleException($ex, 'Failed to create participant.'), 500);
        }
    }

    public function delete(int $id, Request $request)
    {
        try
        {
            /*
             * Find participant
             */
            $participant = Participant::find($id);

            if ($participant === null)
            {
                throw new JsonException(__('Participant not found.'));
            }

            /*
             * Check authorization
             */
            $authorized = BouncerFacade::can('delete', $participant);

            if ($authorized === false)
            {
                throw new JsonException(__('Not authorized.'));
            }

            /*
             * Delete participant
             */
            $participant->delete();

            return response()->json();
        }
        catch(JsonException $jsonEx)
        {
            return response()->json($jsonEx, 500);
        }
        catch (Exception $ex)
        {
            return response()->json($this->handleException($ex, 'Failed to delete participant.'), 500);
        }
    }

    public function validate_email(Request $request)
    {
        try
        {
            /*
             * Check authorization
             */
            $authorized = BouncerFacade::can('read', Participant::class);

            if ($authorized === false)
            {
                throw new JsonException(__('Not authorized.'));
            }

            /*
             * Validate if email address is unique over all participants
             */
            $participant = Participant::where('email', '=', $request->get('address', null))->first();

            return response()->json(['valid' => ($participant === null ? true : false)]);
        }
        catch(JsonException $jsonEx)
        {
            return response()->json($jsonEx, 500);
        }
        catch (Exception $ex)
        {
            return response()->json($this->handleException($ex, 'Failed to validate email.'), 500);
        }
    }

    public function statistics(Request $request)
    {
        try
        {
            /*
             * Check authorization
             */
            // $authorized = BouncerFacade::can('read', Participant::class);

            // if ($authorized === false)
            // {
            //     throw new JsonException(__('Not authorized.'));
            // }

            /*
             * Define months and colors
             */
            $months = explode(',', __('Jan, Feb, Mar, Apr, May, Jun, Jul, Sep, Oct, Nov, Dec'));
            $colors = ['rgb(255, 99, 132)', 'rgb(255, 159, 64)', 'rgb(255, 205, 86)', 'rgb(75, 192, 192)', 'rgb(54, 162, 235)', 'rgb(153, 102, 255)', 'rgb(201, 203, 207)'];

            /*
             * Load created participants of current year
             */
            $created = [];

            array_map(function ($item) use(&$created) {
                /*
                 * Subtract one from real month, because months array starts with zero
                 */
                $created[($item->month - 1)] = $item->count;

            }, DB::select("SELECT MONTH(created_at) AS 'month', COUNT(id) AS 'count' FROM participants WHERE YEAR(created_at) = YEAR(NOW()) GROUP BY YEAR(created_at), MONTH(created_at)"));

            /*
             * Load updated participants of current year
             */
            $updated = [];
            
            array_map(function ($item) use(&$updated) {
                /*
                 * Subtract one from real month, because months array starts with zero
                 */
                $updated[($item->month - 1)] = $item->count;

            }, DB::select("SELECT MONTH(updated_at) AS 'month', COUNT(id) AS 'count' FROM participants WHERE YEAR(updated_at) = YEAR(NOW()) GROUP BY YEAR(updated_at), MONTH(updated_at)"));

            /*
             * Build data
             */
            $data = [
                'labels' => $months,
                'datasets' => [
                    [
                        'label' => __('Created in 2022'),
                        'backgroundColor' => $colors[0],
                        'borderColor' => $colors[0],
                        'data' => array_replace([0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0], $created),
                    ],
                    [
                        'label' => __('Updated in 2022'),
                        'backgroundColor' => $colors[1],
                        'borderColor' => $colors[1],
                        'data' => array_replace([0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0], $updated),
                    ],
                ],
            ];

            return response()->json($data);
        }
        catch(JsonException $jsonEx)
        {
            return response()->json($jsonEx, 500);
        }
        catch (Exception $ex)
        {
            return response()->json($this->handleException($ex, 'Failed to load statistics.'), 500);
        }
    }

    public function send_link(int $id, Request $request)
    {
        try
        {
            /*
             * Find participant
             */
            $participant = Participant::find($id);

            if ($participant === null)
            {
                throw new JsonException(__('Participant not found.'));
            }

            /*
             * Check authorization
             */
            $authorized = BouncerFacade::can('update', $participant);

            if ($authorized === false)
            {
                throw new JsonException(__('Not authorized.'));
            }

            /*
             * Determine locale for participant
             */
            $locale = $participant->language === 'DE' ? 'de' : config('app.fallback_locale');

            /*
             * Get link expiration days
             */
            $expiration = intval(setting('participantpool.updatelink_days'));

            /*
             * Send update link to participant
             */
            $link = URL::temporarySignedRoute('index.register.update', now()->addMinutes($expiration * 1440), ['id' => $participant->id, 'locale' => $locale]);
            
            /*
             * Send update email
             */
            Mail::to($participant->email)->send(new ParticipantUpdateRegistration($participant, 'Update your Registration', $link));

            return response()->json($participant);
        }
        catch(JsonException $jsonEx)
        {
            return response()->json($jsonEx, 500);
        }
        catch (Exception $ex)
        {
            return response()->json($this->handleException($ex, 'Failed to send link.'), 500);
        }
    }
}
