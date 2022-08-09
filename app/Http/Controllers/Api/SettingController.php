<?php

namespace App\Http\Controllers\Api;

use App\Models\Setting;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use JsonException;

class SettingController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function all(Request $request)
    {
        try
        {
            /**
             * @var User
             */
            $user = auth()->user();

            /*
             * Check authorization
             */
            $authorized = $user->isAdmin();

            if ($authorized === false)
            {
                throw new JsonException(__('Not authorized.'));
            }

            /*
             * Load users
             */
            $settings = Setting::all();

            return response()->json($settings);
        }
        catch(JsonException $jsonEx)
        {
            return response()->json($jsonEx, 500);
        }
        catch (Exception $ex)
        {
            return response()->json($this->handleException($ex, 'Failed to load settings.'), 500);
        }
    }

    public function save(int $id, Request $request)
    {
        try
        {
            /**
             * @var User
             */
            $user = auth()->user();

            /*
             * Check authorization
             */
            $authorized = $user->isAdmin();

            if ($authorized === false)
            {
                throw new JsonException(__('Not authorized.'));
            }

            /*
             * Load setting
             */
            $setting = Setting::find($id);

            if ($setting === null)
            {
                throw new JsonException(__('Setting not found.'));
            }

            /*
             * Get input data
             */
            $input = json_decode($request->getContent());

            /*
             * Validate input
             */
            $validator = Validator::make(['value' => $input->data->value], ['value' => 'string']);

            if ($validator->fails())
            {
                throw new JsonException(__('Failed to validate data.'));
            }

            /*
             * Update and save setting
             */
            if (isset($input->data))
            {
                $setting->value = empty($input->data->value) ? null : $input->data->value;

                $setting->save();
            }

            /*
             * Refresh setting
             */
            $setting->refresh();

            return response()->json($setting);
        }
        catch(JsonException $jsonEx)
        {
            return response()->json($jsonEx, 500);
        }
        catch (Exception $ex)
        {
            return response()->json($this->handleException($ex, 'Failed to save setting.'), 500);
        }
    }
}
