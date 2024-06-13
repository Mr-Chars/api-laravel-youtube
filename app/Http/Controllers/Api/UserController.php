<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserCustom;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //
    public function search($id = null)
    {
        $users = null;
        if ($id) {
            $users = UserCustom::where('id', $id)->get();
        } else {
            $users = UserCustom::get();
        }
        return response()->json([
            'status' => true,
            'users' => $users,
        ]);
    }

    public function add(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required|max:30',
            'email' => 'required|max:80',
            'password' => 'required|max:255',
        ]);

        if ($validated->fails()) {
            // validation failed
            $error = $validated->errors()->first();

            return response()->json([
                'status' => false,
                'error' => $error,
            ]);
        }
        // validation passed
        $name = $request->name;
        $email  = $request->email;
        $password  = $request->password;

        $usersExist = UserCustom::where('email', $email)->get();
        if (count($usersExist)) {
            return response()->json([
                'status' => false,
                'error' => 'El email ingresado ya se encuentra registrado',
            ]);
        }

        $dataToSave = [
            'name' => $name,
            'email' => $email,
            'password' => md5($password),
        ];

        try {
            $post = UserCustom::create($dataToSave);

            return response()->json([
                'status' => true,
                'post' => $post,
                'dataToSave' => $dataToSave,
            ]);
        } catch (QueryException $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'id' => 'required',
            'name' => 'required|max:30',
            'email' => 'required|max:80',
            'password' => 'required|max:255',
        ]);

        if ($validated->fails()) {
            // validation failed
            $error = $validated->errors()->first();

            return response()->json([
                'status' => false,
                'error' => $error,
            ]);
        }
        // validation passed
        $id = $request->id;
        $name = $request->name;
        $email  = $request->email;
        $password  = $request->password;

        $usersExistId = UserCustom::where('id', $id)->get();
        if (!count($usersExistId)) {
            return response()->json([
                'status' => false,
                'error' => 'No existe un usuario ingresado con el id ' . $id,
            ]);
        }

        $usersExist = UserCustom::where('email', $email)->get();
        if (count($usersExist) && $usersExist[0]->id != $id) {
            return response()->json([
                'status' => false,
                'error' => 'El email ingresado ya se encuentra registrado',
            ]);
        }

        $dataToUpdate = [
            'name' => $name,
            'email' => $email,
            'password' => md5($password),
        ];

        try {
            $put = UserCustom::where('id', $id)->update($dataToUpdate);

            return response()->json([
                'status' => true,
                'put' => $put,
                'dataToSave' => $dataToUpdate,
            ]);
        } catch (QueryException $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function delete(string $id)
    {

        $usersExistId = UserCustom::where('id', $id)->get();
        if (!count($usersExistId)) {
            return response()->json([
                'status' => false,
                'error' => 'No existe un usuario ingresado con el id ' . $id,
            ]);
        }

        $deletedUser = UserCustom::where('id', $id)->delete();

        return response()->json([
            'status' => true,
            'deletedUser' => $deletedUser,
            'id' => $id,
        ]);
    }
}
