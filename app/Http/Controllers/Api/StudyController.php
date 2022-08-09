<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\JsonException;
use App\Models\Study;
use Exception;
use Illuminate\Http\Request;
use Silber\Bouncer\BouncerFacade;

class StudyController extends ApiController
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
            $authorized = BouncerFacade::can('read', Study::class);

            if ($authorized === false)
            {
                throw new JsonException(__('Not authorized.'));
            }

            /*
             * Read data
             */
            $data = Study::all();

            return response()->json($data);
        }
        catch(JsonException $jsonEx)
        {
            return response()->json($jsonEx, 500);
        }
        catch (Exception $ex)
        {
            return response()->json($this->handleException($ex, 'Failed to read studies.'), 500);
        }
    }

    public function edit(int $id, Request $request)
    {
        try
        {
            /*
             * Find study
             */
            $study = Study::with('mailings')->find($id);

            if ($study === null)
            {
                throw new JsonException(__('Study not found.'));
            }

            /*
             * Check authorization
             */
            $authorized = BouncerFacade::can('read', $study);

            if ($authorized === false)
            {
                throw new JsonException(__('Not authorized.'));
            }

            return response()->json($study);
        }
        catch(JsonException $jsonEx)
        {
            return response()->json($jsonEx, 500);
        }
        catch (Exception $ex)
        {
            return response()->json($this->handleException($ex, 'Failed to edit study.'), 500);
        }
    }

    public function save(int $id, Request $request)
    {
        try
        {
            /*
             * Load study
             */
            $study = Study::find($id);

            if ($study === null)
            {
                throw new JsonException(__('Study not found.'));
            }

            /*
             * Check authorization
             */
            $authorized = BouncerFacade::can('update', $study);

            if ($authorized === false)
            {
                throw new JsonException(__('Not authorized.'));
            }

            /*
             * Get input data
             */
            $input = json_decode($request->getContent());

            /*
             * Update and save study
             */
            if (isset($input->data))
            {
                $study->name = $input->data->name;
                $study->starts_at = $input->data->starts_at ?? null;
                $study->ends_at = $input->data->ends_at ?? null;
                $study->description = $input->data->description ?? null;

                $study->save();
            }

            return response()->json($study);
        }
        catch(JsonException $jsonEx)
        {
            return response()->json($jsonEx, 500);
        }
        catch (Exception $ex)
        {
            return response()->json($this->handleException($ex, 'Failed to save study.'), 500);
        }
    }

    public function create(Request $request)
    {
        try
        {
            /*
             * Check authorization
             */
            $authorized = BouncerFacade::can('create', Study::class);

            if ($authorized === false)
            {
                throw new JsonException(__('Not authorized.'));
            }

            /*
             * Get input data
             */
            $input = json_decode($request->getContent());

            /*
             * Create and save study
             */
            if (isset($input->data))
            {
                $study = new Study();

                $study->name = $input->data->name;
                $study->starts_at = $input->data->starts_at ?? null;
                $study->ends_at = $input->data->ends_at ?? null;

                $study->save();
            }

            return response()->json($study);
        }
        catch(JsonException $jsonEx)
        {
            return response()->json($jsonEx, 500);
        }
        catch (Exception $ex)
        {
            return response()->json($this->handleException($ex, 'Failed to create study.'), 500);
        }
    }

    public function delete(int $id, Request $request)
    {
        try
        {
            /*
             * Load study
             */
            $study = Study::find($id);

            if ($study === null)
            {
                throw new JsonException(__('Study not found.'));
            }

            /*
             * Check authorization
             */
            $authorized = BouncerFacade::can('delete', $study);

            if ($authorized === false)
            {
                throw new JsonException(__('Not authorized.'));
            }

            /*
             * Delete study
             */
            $study->delete();

            return response()->json();
        }
        catch(JsonException $jsonEx)
        {
            return response()->json($jsonEx, 500);
        }
        catch (Exception $ex)
        {
            return response()->json($this->handleException($ex, 'Failed to delete study.'), 500);
        }
    }
}
