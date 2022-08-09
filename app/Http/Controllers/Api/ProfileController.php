<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\JsonException;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function get(Request $request)
    {
        try
        {
            /*
             * Get authenticated user
             */
            $auth = auth()->user();

            /*
             * Find user
             */
            $user = User::find($auth->id);

            if ($user === null)
            {
                throw new JsonException(__('Profile not found.'));
            }

            return response()->json($user);
        }
        catch(JsonException $jsonEx)
        {
            return response()->json($jsonEx, 500);
        }
        catch (Exception $ex)
        {
            return response()->json($this->handleException($ex, 'Failed to get profile.'), 500);
        }
    }

    public function save(Request $request)
    {
        try
        {
            /*
             * Get authenticated user
             */
            $auth = auth()->user();

            /*
             * Load user
             */
            $user = User::find($auth->id);

            if ($user === null)
            {
                throw new JsonException(__('Profile not found.'));
            }

            /*
             * Get input data
             */
            $input = json_decode($request->getContent());

            /*
             * Validate input
             */
            $validator = Validator::make([
                'name' => $input->data->name,
                'password' => $input->data->password ?? '',
                'password_confirmation' => $input->data->password_confirmation ?? '',
            ], [
                'name' => 'required|string',
                'password' => 'string|min:8|confirmed',
            ]);

            if ($validator->fails())
            {
                throw new JsonException(__('Failed to validate data.'));
            }

            /*
             * Update and save study
             */
            if (isset($input->data))
            {
                $user->name = $input->data->name;

                if ($input->data->password ?? false)
                {
                    $user->password = Hash::make($input->data->password);
                }

                $user->save();
            }

            /*
             * Refresh user
             */
            $user->refresh();

            return response()->json($user);
        }
        catch(JsonException $jsonEx)
        {
            return response()->json($jsonEx, 500);
        }
        catch (Exception $ex)
        {
            return response()->json($this->handleException($ex, 'Failed to save profile.'), 500);
        }
    }
}
