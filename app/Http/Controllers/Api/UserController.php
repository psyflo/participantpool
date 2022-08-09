<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\JsonException;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Silber\Bouncer\BouncerFacade;

class UserController extends ApiController
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
             * Get current user
             */
            $user = auth()->user();

            /*
             * Check authorization
             */
            $authorized = BouncerFacade::can('read', User::class);

            if ($authorized === false)
            {
                throw new JsonException(__('Not authorized.'));
            }

            /*
             * Load users
             */
            $users = User::all();

            return response()->json($users);
        }
        catch(JsonException $jsonEx)
        {
            return response()->json($jsonEx, 500);
        }
        catch (Exception $ex)
        {
            return response()->json($this->handleException($ex, 'Failed to load users.'), 500);
        }
    }

    public function edit(int $id, Request $request)
    {
        try
        {
            /*
             * Get authenticated user
             */
            $auth = auth()->user();

            /*
             * Check authorization
             */
            $authorized = BouncerFacade::can('read', User::class);

            if ($authorized === false)
            {
                throw new JsonException(__('Not authorized.'));
            }

            /*
             * Find user
             */
            $user = User::find($id);

            if ($user === null)
            {
                throw new JsonException(__('User not found.'));
            }

            return response()->json($user);
        }
        catch(JsonException $jsonEx)
        {
            return response()->json($jsonEx, 500);
        }
        catch (Exception $ex)
        {
            return response()->json($this->handleException($ex, 'Failed to edit user.'), 500);
        }
    }

    public function save(int $id, Request $request)
    {
        try
        {
            /*
             * Get authenticated user
             */
            $auth = auth()->user();

            /*
             * Check authorization
             */
            $authorized = BouncerFacade::can('update', User::class);

            if ($authorized === false)
            {
                throw new JsonException(__('Not authorized.'));
            }

            /*
             * Load user
             */
            $user = User::find($id);

            if ($user === null)
            {
                throw new JsonException(__('User not found.'));
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
                'role' => $input->data->role,
            ], [
                'name' => 'required|string',
                'role' => ['string', Rule::in(['disabled', 'manager', 'admin'])],
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

                $user->save();
            }

            /*
             * Update user role
             */
            $user->updateRole($input->data->role ?? null);

            /*
             * Refresh roles for user
             */
            BouncerFacade::refreshFor($user);

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
            return response()->json($this->handleException($ex, 'Failed to save user.'), 500);
        }
    }

    public function create(Request $request)
    {
        try
        {
            /*
             * Get authenticated user
             */
            $auth = auth()->user();

            /*
             * Check authorization
             */
            $authorized = BouncerFacade::can('create', User::class);

            if ($authorized === false)
            {
                throw new JsonException(__('Not authorized.'));
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
                'email' => $input->data->email,
                'password' => $input->data->password,
                'password_confirmation' => $input->data->password_confirmation,
                'role' => $input->data->role,
            ], [
                'name' => 'required|string',
                'email' => 'required|string|email|unique:users,email',
                'password' => 'required|string|min:8|confirmed',
                'role' => ['string', Rule::in(['disabled', 'manager', 'admin'])],
            ]);
     
            if ($validator->fails())
            {
                throw new JsonException(__('Failed to validate data.'), $validator->errors()->getMessages());
            }

            /*
             * Create and save study
             */
            if (isset($input->data))
            {
                $user = new User();

                $user->name = $input->data->name;
                $user->email = $input->data->email;
                $user->password = Hash::make($input->data->password);

                $user->save();
            }

            /*
             * Update user role
             */
            $user->updateRole($input->data->role ?? null);

            /*
             * Refresh roles for user
             */
            BouncerFacade::refreshFor($user);

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
            return response()->json($this->handleException($ex, 'Failed to create user.'), 500);
        }
    }

    public function delete(int $id, Request $request)
    {
        try
        {
            /*
             * Check authorization
             */
            $authorized = BouncerFacade::can('delete', User::class);

            if ($authorized === false)
            {
                throw new JsonException(__('Not authorized.'));
            }

            /*
             * Load user
             */
            $user = User::find($id);

            if ($user === null)
            {
                throw new JsonException(__('User not found.'));
            }

            /*
             * Do not allow to delete myself
             */
            if ($user->id === auth()->user()->id)
            {
                throw new JsonException(__('Authenticated user cannot be deleted.'));
            }

            /*
             * Delete user
             */
            $user->delete();

            return response()->json();
        }
        catch(JsonException $jsonEx)
        {
            return response()->json($jsonEx, 500);
        }
        catch (Exception $ex)
        {
            return response()->json($this->handleException($ex, 'Failed to delete user.'), 500);
        }
    }
}
