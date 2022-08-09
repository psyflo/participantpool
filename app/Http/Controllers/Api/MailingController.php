<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\JsonException;
use App\Models\Mailing;
use App\Models\MailingParticipant;
use App\Models\Study;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Silber\Bouncer\BouncerFacade;

class MailingController extends ApiController
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
             * Check authorization
             */
            $authorized = BouncerFacade::can('read', Mailing::class);

            if ($authorized === false)
            {
                throw new JsonException(__('Not authorized.'));
            }

            /*
             * Read data
             */
            $data = Mailing::with(['owner', 'study'])->get();

            return response()->json($data);
        }
        catch(JsonException $jsonEx)
        {
            return response()->json($jsonEx, 500);
        }
        catch (Exception $ex)
        {
            return response()->json($this->handleException($ex, 'Failed to read mailings.'), 500);
        }
    }

    public function remove_participant(int $id, int $participant_id, Request $request)
    {
        try
        {
             /*
             * Find mailing
             */
            $mailing = Mailing::find($id);

            if ($mailing === null)
            {
                throw new JsonException(__('Mailing not found.'));
            }

            /*
             * Check authorization
             */
            $authorized = BouncerFacade::can('update', $mailing);

            if ($authorized === false)
            {
                throw new JsonException(__('Not authorized.'));
            }

            /*
             * Find mailing participant to remove
             */
            $participant = MailingParticipant::where('mailing_id', '=', $mailing->id)->where('id', '=', $participant_id)->first();

            if ($participant === null)
            {
                throw new JsonException(__('Mailing participant not found.'));
            }

            /*
             * Remove mailing participant
             */
            $participant->delete();

            return $this->edit($id, $request);
        }
        catch(JsonException $jsonEx)
        {
            return response()->json($jsonEx, 500);
        }
        catch (Exception $ex)
        {
            return response()->json($this->handleException($ex, 'Failed to remove participant.'), 500);
        }
    }

    public function create(Request $request)
    {
        try
        {
            /*
             * Check authorization
             */
            $authorized = BouncerFacade::can('create', Mailing::class);

            if ($authorized === false)
            {
                throw new JsonException(__('Not authorized.'));
            }
            
            /*
             * Get JSON input data
             */
            $data = $request->input();

            /*
             * Create and save mailing
             */
            $mailing = new Mailing();
            $mailing->name = $data['mailing']['name'];
            $mailing->user_id = auth()->user()->id;
            $mailing->content = '';

            /*
             * Associate study, create it if required
             */
            if ($data['mailing']['study_id'] === 0)
            {
                $study = new Study();

                $study->name = $mailing->name;
                $study->save();

                $mailing->study_id = $study->id;
            }
            else
            {
                $mailing->study_id = $data['mailing']['study_id'];
            }

            /*
             * Save data
             */
            $mailing->save();

            /*
             * Check random value to shuffle participants
             */
            if ($data['mailing']['random'] !== null && $data['mailing']['random'] > 0 && $data['mailing']['random'] < count($data['participants']))
            {
                /*
                 * Shuffle three times, all good things come in threes
                 */
                shuffle($data['participants']);
                shuffle($data['participants']);
                shuffle($data['participants']);
            }

            /*
             * Associate mailing to participants
             */
            for ($curent = 0; $curent < $data['mailing']['random']; $curent++)
            {
                $mailing->participants()->save(new MailingParticipant(['participant_id' => $data['participants'][$curent]['id'], 'mailing_id' => $mailing->id]));
            }

            /*
             * Refresh mailing
             */
            $mailing->refresh();

            return response()->json($mailing);
        }
        catch(JsonException $jsonEx)
        {
            return response()->json($jsonEx, 500);
        }
        catch (Exception $ex)
        {
            return response()->json($this->handleException($ex, 'Failed to create mailing.'), 500);
        }
    }

    public function edit(int $id, Request $request)
    {
        try
        {
            /*
             * Find mailing
             */
            $mailing = Mailing::with(['owner', 'study', 'participants', 'participants.participant'])->find($id);

            if ($mailing === null)
            {
                throw new JsonException(__('Mailing not found.'));
            }

            /*
             * Check authorization
             */
            $authorized = BouncerFacade::can('read', $mailing);

            if ($authorized === false)
            {
                throw new JsonException(__('Not authorized.'));
            }

            return response()->json($mailing);
        }
        catch(JsonException $jsonEx)
        {
            return response()->json($jsonEx, 500);
        }
        catch (Exception $ex)
        {
            return response()->json($this->handleException($ex, 'Failed to edit mailing.'), 500);
        }
    }

    public function save(int $id, Request $request)
    {
        try
        {
            /*
             * Validate input
             */
            $validator = Validator::make($request->input(),
            [
                'name' => 'required|string|max:200',
                'subject' => 'required|string',
                'content' => 'required|string',
            ]);
     
            if ($validator->fails())
            {
                throw new JsonException(__('Validation failed.'));
            }

            /*
             * Find mailing
             */
            $mailing = Mailing::with(['owner', 'participants', 'participants.participant'])->find($id);

            if ($mailing === null)
            {
                throw new JsonException(__('Mailing not found.'));
            }

            /*
             * Check authorization
             */
            $authorized = BouncerFacade::can('update', $mailing);

            if ($authorized === false)
            {
                throw new JsonException(__('Not authorized.'));
            }

            /*
             * Get JSON input data
             */
            $data = $request->input();

            /*
             * Update and save mailing
             */
            $mailing->name = $data['name'];
            $mailing->state = $data['state'];
            $mailing->subject = $data['subject'];
            $mailing->content = $data['content'];

            $mailing->save();

            return response()->json($mailing);
        }
        catch(JsonException $jsonEx)
        {
            return response()->json($jsonEx, 500);
        }
        catch (Exception $ex)
        {
            return response()->json($this->handleException($ex, 'Failed to save mailing.'), 500);
        }
    }

    public function delete(int $id, Request $request)
    {
        try
        {
            /*
             * Find mailing
             */
            $mailing = Mailing::find($id);

            if ($mailing === null)
            {
                throw new JsonException(__('Mailing not found.'));
            }

            /*
             * Check authorization
             */
            $authorized = BouncerFacade::can('delete', $mailing);

            if ($authorized === false)
            {
                throw new JsonException(__('Not authorized.'));
            }

            /*
             * Delete mailing
             */
            $mailing->delete();

            return response()->json();
        }
        catch(JsonException $jsonEx)
        {
            return response()->json($jsonEx, 500);
        }
        catch (Exception $ex)
        {
            return response()->json($this->handleException($ex, 'Failed to delete mailing.'), 500);
        }
    }
}
