<?php

namespace App\Http\Controllers;

use App\User;
use App\Setting;
use App\Application;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserDashboard extends Controller
{
    //--> Show User
    public function ShowUsers() {
        
    $data = User::
        // where('applications.id_user', '=', $id)            
        leftJoin('icons', 'users.id_icons', '=', 'icons.id_icons')
        ->select(
            'users.name as username',
            'users.email as email',
            'users.role as role',
            'icons.url as photo'
        )
        ->orderBy('users.created_at', 'DESC')
        ->get();

    return response()->json([
        'success'   => true,
        'message'   => 'Data Ditemukan',
        'data' => $data
        ]);
    }

    //--> Insert User
    public function AddUsers(Request $request) {

        $validator = Validator::make($request->all(), [
            'item.username' => 'required|string',
            'item.email' => 'required|string',
            'item.password' => 'required|string',
            ]);
        
        if($validator->fails()){
            $error = $validator->errors()->first();
            return response()->json(['message' => $error], 400);
        } else {
            $insertData = User::create([
                'name' => $request->input('item.username'),
                'email' => $request->input('item.email'),
                'password' => Hash::make($request->input('item.password')),
                'role' => 'member',
                'id_icons' => 1
            ]);
        }
        $data = User::
            where('users.id_user', '=', $insertData->id_user)            
            ->leftJoin('icons', 'users.id_icons', '=', 'icons.id_icons')
            ->select(
                'users.name as username',
                'users.email as email',
                'users.role as role',
                'icons.url as photo'
            )
            ->get()->first();
    
        return response()->json([
            'success'   => true,
            'message'   => 'Data Ditemukan',
            'data' => $data
        ]);

    }

    // App Limits
    public function limitApps() {
        $data = Application::
            leftJoin('users', 'applications.id_user', '=', 'users.id_user')
            ->leftJoin('icons', 'applications.id_icons', '=', 'icons.id_icons')
            ->select(
                'applications.id_applications',
                'applications.id_user',
                'applications.id_icons',
                'applications.name_applications',
                'applications.package_applications',
                'applications.key_applications',
                'applications.limit_applications',
                'applications.created_at',
                'users.name',
                'users.email',
                'users.role',
                'icons.folder',
                'icons.extension',
                'icons.url'
            )
            ->orderBy('applications.created_at', 'DESC')
            ->get();

        return response()->json([
            'success'   => true,
            'message'   => 'Data Ditemukan',
            'data' => $data
        ]);
    }
    public function limitAppsEdit(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'id_user' => 'required',
            'count' => 'required',
            ]);
        
        if($validator->fails()){
            $error = $validator->errors()->first();
            return response()->json([
                'success'   => false,
                'message'   => 'Update Failed',
                'error' => $error
            ]);
        } else {
        $id_user = $request->input('id_user');
        $data = User::
            where('users.id_user', '=', $id_user)
            ->leftJoin('icons', 'users.id_icons', '=', 'icons.id_icons')
            ->select(
                'users.name as username',
                'users.email as email',
                'users.role as role',
                'icons.url as photo',
            )
            ->get()->first();
        if ($data->role == 'admin') {
            Application::where('applications.id_applications', '=', $request->input('id'))
                ->update([
                    'limit_applications' => $request->input('count')
                ]);
                return response()->json([
                    'success'   => true,
                    'message'   => 'Update Successful',
                    'data'   => $request->input('count'),
                ]);
            } else {
                return response()->json([
                    'success'   => false,
                    'message'   => 'Update Failed',
                ]);
            }
        }
    }
    public function EditUsers(Request $request) {
        $data = json_decode($request->item);
        $id = $data->id;
        $users = User::where('users.id_user', '=', $id)->get()->first();

        if($users == $users->id_users) {
            User::where('users.id_user', '=', $id)
                ->update([
                    'name' => $data->username
                ]);
                $dataUser = User::
                    where('users.id_user', '=', $id)            
                    ->leftJoin('icons', 'users.id_icons', '=', 'icons.id_icons')
                    ->select(
                        'users.name as username',
                        'users.email as email',
                        'users.role as role',
                        'icons.url as photo'
                    )
                    ->orderBy('users.created_at', 'DESC')
                    ->get();
            return response()->json([
                'success'   => true,
                'message'   => 'Update Successful',
                'data' => $dataUser
            ]);

        } else {
            return response()->json([
                'success'   => false,
                'message'   => 'Update Failed',
            ]);
        }

    }
}
