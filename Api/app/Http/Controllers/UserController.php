<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{

    function index()
    {
        $merge = [];
        $users = User::all();
        foreach ($users as $user) {
            $userApi = Controller::apiUserCodigo($user->code)->json();
            unset($userApi['id']);
            $fullUser = array_merge($userApi, $user->toArray());
            $merge[] = $fullUser;
        }
        //Delete the first user ( admin )
        array_shift($merge);
        return UserCollection::make($merge);
    }

    function create(CreateUserRequest $request)
    {
        //Request  extern api
        $codeUserApi = $request->input('data.attributes.code');
        $response = Controller::apiUserCodigo($codeUserApi);


        // Check if user is already created
        if (User::where('code', $codeUserApi)->first()) {
            $exception = ValidationException::withMessages([
                "data.attributes.code" => "The user has already been created"
            ]);
            $exception->status = 409;
            throw $exception;
        }

        // Code was found in extern api
        if ($response->successful()) {
            $userApi = $response->json();
            $user = new User();
            $user->user_name = $userApi['nombre'] . '_' . $userApi['apellidos'];
            $user->code = $userApi['codigo'];
            $user->email = $userApi['email'];
            $user->password = Hash::make($request->input('data.attributes.password'));

            // Check rol
            $userTipo = $userApi['tipo'];
            ($userTipo == 'Estudiante') ? $user->role_id = 2 : ($userTipo == 'Profesor' ? $user->role_id = 3 : $user->role_id = 1);
            $user->state = '1';
            //Create in data base using request's data
            $user->save();
            //Return a resource with user created
            return UserResource::make($user);
        }

        //Response extern api - code not found
        if ($response->status() == 404) {
            $exception = ValidationException::withMessages([
                "data.attributes.code" => "The user code provided is not valid"
            ]);
            $exception->status = 403;
            throw $exception;
        }
    }

    function show(User $user)
    {
        return UserResource::make($user);
    }

    function filterUser($filter)
    {
        $merge = [];
        $users = User::where('user_name', 'LIKE', '%' . $filter . '%')
            ->orWhere('state', 'LIKE', ($filter == "Activo" || $filter == "activo") ? 1 : (($filter == "Inactivo" || $filter == "Inactivo") ? 0 : 3))
            ->get();
        foreach ($users as $user) {
            $userApi = Controller::apiUserCodigo($user->code)->json();
            unset($userApi['id']);
            $fullUser = array_merge($userApi, $user->toArray());
            $merge[] = $fullUser;
        }
        //Delete the first user ( admin )
        array_shift($merge);
        return UserCollection::make($merge);
    }
}
