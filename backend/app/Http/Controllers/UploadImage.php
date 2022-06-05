<?php

namespace App\Http\Controllers;

use App\Application;
use App\Category;
use App\Icon;
use App\Image;
use App\Setting;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

use Illuminate\Support\Facades\Validator;

class UploadImage extends Controller
{
    public function showApplicationCategory($id) {

        $data = Application::
            with(['CategoryAssets' => function($dataJoin) {
                $dataJoin->orderBy('created_at', 'DESC');     
            }])
            ->with(['ImageAssets' => function($dataJoin) {
                $dataJoin
                ->select('id_images','id_applications'); 
            }])
            ->where('id_user', '=', ''.$id)
            ->orderBy('applications.created_at', 'DESC')    
            ->get();

        return response()->json([
            'success'   => true,
            'message'   => 'Data Ditemukan',
            'data' => $data
        ]);

    }

    public function uploadImage(Request $request, $app, $category) {
        
        $setting = Setting::get()->first();

        $request->hasFile('file');
        $image = $request->file('file');    
        $folder = 'assets';
        $name = Str::random(10);
        $ex = $image->extension();
        $imageName = $name.'.'.$image->extension();  
        $image->move(public_path($folder), $imageName);

        Image::create([
            'id_applications' => $app,
            'id_categories' => $category,
            'folder' => $folder,
            'name' => $name,
            'extension' => $ex,
            'status' => 'assets',
            'position' => 'public',
            'url' => $setting->url.$folder.'/'.$imageName,
        ]);

        return response()->json([
            'success'   => true,
            'message' => 'success'
        ], 200);
    }
    public function uploadImageUrl(Request $request) {
        $validator = Validator::make($request->all(), [
            'id_applications' => 'required',
            'category' => 'required',
            'url' => 'required',
            'name' => 'required',
            'type' => 'required'

            ]);
        if($validator->fails()){
            $error = $validator->errors()->first();
            return response()->json([
                'success'   => false,
                'message' => $error
            ], 200);
        } else {
            
        Image::create([
            'id_applications' => $request->input('id_applications'),
            'id_categories' => $request->input('category'),
            'folder' => 'Tidak Ada',
            'name' => $request->input('name'),
            'extension' => 'Tidak Ada',
            'status' => 'url',
            'position' => 'public',
            'url' => $request->input('url'),
        ]);
        return response()->json([
            'success'   => true,
            'message' => 'Data Input Succesful'
        ], 200);
        }
    }

    public function deleteImage($id, $id_app) {

        $getData = Image::where('id_images', '=', $id)
                    ->where('id_applications', '=', $id_app)
                    ->get()->first();
        if ($getData->status == 'url') {
            $post = Image::findOrFail($id);
            $post->delete();
            return response()->json([
                'success' => true,
                'message' => 'Delete Success',
                'data'    => $id,
            ], 200);
        } else {
            $image_path = $getData->folder.'/'.$getData->name.'.'.$getData->extension;
            if (File::exists($image_path)) {
                File::delete($image_path);
                $success = true;
            } else {
                $success = false;
            }
            if ($success = true) {
                $post = Image::findOrFail($id);
                $post->delete();
                return response()->json([
                    'success' => true,
                    'message' => 'Delete Success',
                    'data'    => $id,
                ], 200);
            }
        }

    }
    public function deleteImageCategory($id, $id_app) {

        $getData = Image::where('id_images', '=', $id)
                    ->where('id_categories', '=', $id_app)
                    ->get()->first();
        if ($getData->status == 'url') {
            $post = Image::findOrFail($id);
            $post->delete();
            return response()->json([
                'success' => true,
                'message' => 'Delete Success',
                'data'    => $id,
            ], 200);
        } else {
            $image_path = $getData->folder.'/'.$getData->name.'.'.$getData->extension;
            if (File::exists($image_path)) {
                File::delete($image_path);
                $success = true;
            } else {
                $success = false;
            }
            if ($success = true) {
                $post = Image::findOrFail($id);
                $post->delete();
                return response()->json([
                    'success' => true,
                    'message' => 'Delete Success',
                    'data'    => $id,
                ], 200);
            }
        }

    }

}
