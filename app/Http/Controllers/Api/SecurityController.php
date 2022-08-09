<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\JsonException;
use App\Models\Mailing;
use App\Models\Participant;
use App\Models\Study;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Silber\Bouncer\BouncerFacade;

class SecurityController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function get(Request $request)
    {
        try
        {
            /**
             * @var User
             */
            $user = auth()->user();

            /*
             * Prepare data
             */
            $data = [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'abilities' => [
                    'participant' => [
                        'create' => BouncerFacade::can('create', Participant::class),
                        'read' => BouncerFacade::can('read', Participant::class),
                        'update' => BouncerFacade::can('update', Participant::class),
                        'delete' => BouncerFacade::can('delete', Participant::class),
                    ],
                    'study' => [
                        'create' => BouncerFacade::can('create', Study::class),
                        'read' => BouncerFacade::can('read', Study::class),
                        'update' => BouncerFacade::can('update', Study::class),
                        'delete' => BouncerFacade::can('delete', Study::class),
                    ],
                    'mailing' => [
                        'create' => BouncerFacade::can('create', Mailing::class),
                        'read' => BouncerFacade::can('read', Mailing::class),
                        'update' => BouncerFacade::can('update', Mailing::class),
                        'delete' => BouncerFacade::can('delete', Mailing::class),
                    ],
                    'user' => [
                        'create' => BouncerFacade::can('create', User::class),
                        'read' => BouncerFacade::can('read', User::class),
                        'update' => BouncerFacade::can('update', User::class),
                        'delete' => BouncerFacade::can('delete', User::class),
                    ],
                ],
                'roles' => [
                    'admin' => $user->isAdmin(),
                    'manager' => $user->isManager(),
                ]
            ];

            return response()->json($data);
        }
        catch(JsonException $jsonEx)
        {
            return response()->json($jsonEx, 500);
        }
        catch (Exception $ex)
        {
            return response()->json($this->handleException($ex, 'Failed to load security.'), 500);
        }
    }
}
