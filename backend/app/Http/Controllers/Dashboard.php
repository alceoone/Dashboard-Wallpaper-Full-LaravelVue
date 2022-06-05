<?php

namespace App\Http\Controllers;

use App\Application;
use App\Category;
use App\Icon;
use App\Image;
use App\Setting;
use App\User;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File; 

use Illuminate\Support\Facades\Validator;
class Dashboard extends Controller
{

    //--> Dashboard
    public function Dashboard($id) {
        $data = Application::
            where('applications.id_user', '=', $id)
            ->limit('7')
            ->get();
        $countApp = Application::
            where('applications.id_user', '=', $id)
            ->get();
        $countCatApp = Category::
            where('categories.id_user', '=', $id)
            ->get();
        return response()->json([
            'success'   => true,
            'message' => 'data berhasil di Ambil',
            'data' => $data,
            'countApp' => $countApp->count(),
            'countCatApp' => $countCatApp->count(),
        ], 200);
    }

    //--> Application
    public function ApplicationShow($id) {
        
        $data = Application::
            where('applications.id_user', '=', $id)            
            ->leftJoin('icons', 'applications.id_icons', '=', 'icons.id_icons')
            // ->leftJoin('users', 'applications.id_user', '=', 'users.id_user')
            ->select(
                'applications.id_applications',
                'applications.id_icons',
                'applications.name_applications',
                'applications.package_applications',
                'applications.key_applications',
                'applications.limit_applications',
                'icons.folder',
                'icons.name',
                'icons.extension',
                'icons.url',
                'applications.created_at'
            )
            ->orderBy('applications.created_at', 'DESC')
            ->get();

        
        return response()->json([
            'success'   => true,
            'message' => 'data berhasil di Ambil',
            'data' => $data
        ], 200);
    }

    //--> Application Count
    public function ApplicationShowCountId($id, $id_user) {
        
        $image = Image::
        where('images.id_applications', '=', $id)
        ->select(
            'id_images',
            'id_applications',
            'id_categories',
            'url',
        )
        ->get();
        
        $data = Application::
            where('applications.id_applications', '=', $id)   
            ->where('applications.id_user', '=', $id_user)       
            ->leftJoin('icons', 'applications.id_icons', '=', 'icons.id_icons')
            ->select(
                'applications.id_applications',
                'applications.id_icons',
                'applications.name_applications',
                'applications.package_applications',
                'applications.key_applications',
                'applications.limit_applications',
                'icons.folder',
                'icons.name',
                'icons.extension',
                'icons.url',
                'applications.created_at'
            )
            ->orderBy('applications.created_at', 'DESC')
            ->get()->first();
            if (!$data) {
                return response()->json([
                    'success'   => false,
                    'message' => 'application not found',
                ], 200);
            } else {
            return response()->json([
                'success'   => true,
                'message' => 'data berhasil di Ambil',
                'data' => $data,
                'image' => $image
            ], 200);
        }
    }

    public function ApplicationShowById($id, $id_user) {
        $data = Application::
            where('applications.id_applications', '=', $id)
            ->where('applications.id_user', '=', $id_user)            
            ->leftJoin('icons', 'applications.id_icons', '=', 'icons.id_icons')
            ->select(
                'applications.id_applications',
                'applications.id_icons',
                'applications.name_applications',
                'applications.package_applications',
                'applications.key_applications',
                'applications.limit_applications',
                'icons.folder',
                'icons.name',
                'icons.extension',
                'icons.url',
                'applications.created_at'
            )
            ->orderBy('applications.created_at', 'DESC')
            ->get()->first();
            if (!$data) {
                return response()->json([
                    'success'   => false,
                    'message' => 'application not found',
                ], 200);
            } else {    
                return response()->json([
                    'success'   => true,
                    'message' => 'data berhasil di Ambil',
                    'data' => $data
                ], 200);
            }
    }

    public function ApplicationInsert(Request $request) {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:1024'
        ]);
        if($validator->fails()){
            $error = $validator->errors()->first();
            return response()->json([
                'success'   => false,
                'message' => $error
            ], 200);
        } else {
            
            $folderIcons = 'application';
            $idIcon = $this->uploadFoto($request, $folderIcons);
            
            $dateNow = Carbon::now();
            $keyUnique = 
            Str::random(3).
            $dateNow->day.
            Str::random(10).
            $dateNow->month.
            Str::random(7).
            $dateNow->year.
            Str::random(2);
        
            $data = json_decode($request->item);
        
            $insetData = Application::create([
                'id_user' => $data->id_user,
                'id_icons' => $idIcon,
                'name_applications' => $data->application,
                'package_applications' => $data->package,
                'key_applications' => $keyUnique,
                'limit_applications' => 50
            ]);
        }
        return response()->json([
            'success'   => true,
            'message' => 'Data Success Insert'
        ], 200);
    }

    public function ApplicationEdit(Request $request) {
        $data = json_decode($request->item);

        if ($data->editIcon == false) {
            Application::whereid_applications($data->id_application)->update([
                'name_applications' => $data->application,
                'package_applications' => $data->package,
            ]);
            return response()->json([
                'success'   => true,
                'message' => 'Data Success Update'
            ], 200);
        } else {
            $id = $data->id_application;
            $folderIcons = 'application';
            $updateIcon = $this->updateFoto($request, $id, $folderIcons);
            
            return response()->json([
                'success'   => true,
                'message' => 'Data and Icons Success Update',
            ], 200);   
        }
    }

    public function ApplicationDelete($id){
        $dataApplication = Application::
                where('applications.id_applications', '=', $id)
                ->leftJoin('icons', 'applications.id_icons', '=', 'icons.id_icons')
                ->get()->first();
        
        $image_path = $dataApplication->folder.'/'.$dataApplication->name.'.'.$dataApplication->extension;
            if (File::exists($image_path)) {
                File::delete($image_path);
                $success = true;
            } else {
                $success = false;
            }
            if ($success = true) {
                $post = Application::findOrFail($id);
                $post->delete();
                $icon = Icon::findOrFail($dataApplication->id_icons);
                $icon->delete();   
            }
        if ($post) {
            $c = Category::where('categories.id_applications', '=', $id)
                ->leftJoin('icons', 'categories.id_icons', '=', 'icons.id_icons')
                ->get();
            // $c->delete();
            foreach ($c as $key => $getData) {
                $image_path = $getData->folder.'/'.$getData->name.'.'.$getData->extension;
                if (File::exists($image_path)) {
                    File::delete($image_path);
                    $success = true;
                } else {
                    $success = false;
                }
                if($success == true) {
                    
                    $ca = Category::where('categories.id_applications', '=', $id);
                    $ca->delete();
                }
            }

            $getImage = Image::
                where('images.id_applications', '=', $id)
                ->get();
            if (!$getImage) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data Berhasil Dihapus!',
                    'data'    => array(),
                ], 200);
            
            } else {
                foreach ($getImage as $key => $getData) {
                    $image_path = $getData->folder.'/'.$getData->name.'.'.$getData->extension;
                    if (File::exists($image_path)) {
                        File::delete($image_path);
                        $success = true;
                    } else {
                        $success = false;
                    }
                    if($success == true) {
                        $post = Image::where('images.id_applications', '=', $id);
                        $post->delete();
                    }
                }
                
                    return response()->json([
                        'success' => true,
                        'message' => 'Data Berhasil Dihapus!',
                        'data'    => array(),
                    ], 200);
                }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data Gagal Dihapus!',
                'data'    => array(),
            ], 500);
        }
    }

    //--> Category
    public function CategoryShow($id) {
        $data = Category::where('categories.id_user', '=',$id)
            ->leftJoin('icons', 'categories.id_icons', '=', 'icons.id_icons')
            ->leftJoin('applications', 'categories.id_applications', '=', 'applications.id_applications')
            ->orderBy('categories.created_at', 'DESC')
            ->get();
        return response()->json([
            'success'   => true,
            'message' => 'Data Success',
            'data' => $data
        ], 200);
    }
    public function CategoryShowById($id) {

        $data = Category::where('categories.id_categories', '=',$id)
            ->leftJoin('icons', 'categories.id_icons', '=', 'icons.id_icons')
            ->leftJoin('applications', 'categories.id_applications', '=', 'applications.id_applications')
            ->orderBy('categories.created_at', 'DESC')
            ->get()->first();
        $image = Image::where('images.id_categories', '=', $id)
        ->select(
            'id_images',
            'id_applications',
            'id_categories',
            'url',
        )
        ->get();

        return response()->json([
            'success'   => true,
            'message' => 'Data Success',
            'data' => $data,
            'image' => $image
        ], 200);
    }

    public function CategoryInsert(Request $request) {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:512'
        ]);
        if($validator->fails()){
            $error = $validator->errors()->first();
            return response()->json([
                'success'   => false,
                'message' => $error
            ], 200);
        } else {
            
            $folderIcons = 'category';
            $idIcon = $this->uploadFoto($request, $folderIcons);
            
            
            $data = json_decode($request->item);
        
            Category::create([
                'id_user' => $data->id_user,
                'id_icons' => $idIcon,
                'id_applications' => $data->id_application,
                'name_categories' => $data->category,
            ]);
        }
        return response()->json([
            'success'   => true,
            'message' => 'Data Success Insert'
        ], 200);
    }

    public function CategoryEdit(Request $request) {
        $data = json_decode($request->item);

        if ($data->editIcon == false) {
            Category::whereid_categories($data->id_categories)->update([
                'name_applications' => $data->categories,
            ]);
            return response()->json([
                'success'   => true,
                'message' => 'Data Success Update'
            ], 200);
        } else {
            $id = $data->id_categories;
            $folderIcons = 'category';
            $updateIcon = $this->updateFotoCategory($request, $id, $folderIcons);
            
            return response()->json([
                'success'   => true,
                'message' => 'Data and Icons Success Update',
            ], 200);   
        }
    }

    public function CategoryDelete($id) {
        $dataApplication = Category::
                where('categories.id_categories', '=', $id)
                ->leftJoin('icons', 'categories.id_icons', '=', 'icons.id_icons')
                ->get()->first();
        
        $image_path = $dataApplication->folder.'/'.$dataApplication->name.'.'.$dataApplication->extension;
            if (File::exists($image_path)) {
                File::delete($image_path);
                $success = true;
            } else {
                $success = false;
            }
            if ($success = true) {
                $post = Category::findOrFail($id);
                $post->delete();
                $icon = Icon::findOrFail($dataApplication->id_icons);
                $icon->delete();   
                
                $getImage = Image::
                    where('images.id_categories', '=', $id)
                    ->get();
                foreach ($getImage as $key => $getData) {
                    $image_path = $getData->folder.'/'.$getData->name.'.'.$getData->extension;
                    if (File::exists($image_path)) {
                        File::delete($image_path);
                        $success = true;
                    } else {
                        $success = false;
                    }
                    if($success == true) {
                        $post = Image::where('images.id_categories', '=', $id);
                        $post->delete();
                    }
                }
            }
        if ($post) {
            return response()->json([
                'success' => true,
                'message' => 'Data Berhasil Dihapus!',
                'data'    => array(),
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data Gagal Dihapus!',
                'data'    => array(),
            ], 500);
        }
    }

    //--> Icon Upload
    function uploadFoto($request, $folderIcons) {
        $request->hasFile('file');
        $image = $request->file('file');    
        $folder = $folderIcons;
        $name = Str::random(10);
        $ex = $image->extension();
        $imageName = $name.'.'.$image->extension();  
        $image->move(public_path($folder), $imageName);

        $setting = Setting::get()->first();

        $insertData = Icon::create([
            'folder' => $folderIcons,
            'name' => $name,
            'extension' => $ex,
            'url' => $setting->url.$folderIcons.'/'.$imageName,
        ]);

        return $insertData->id_icons;
    }
    //--> Imange Update
    function updateFoto($request, $id, $folderIcons) {
        $dataApplication = Application::
                where('applications.id_applications', '=', $id)
                ->leftJoin('icons', 'applications.id_icons', '=', 'icons.id_icons')
                ->get()->first();
        
        $image_path = $dataApplication->folder.'/'.$dataApplication->name.'.'.$dataApplication->extension;
            if (File::exists($image_path)) {
                File::delete($image_path);
                $success = true;
            } else {
                $success = false;
            }
            if ($success = true) {
        
                $request->hasFile('file');
                $image = $request->file('file');    
                $folder = $folderIcons;
                $name = $dataApplication->name;
                $ex = $image->extension();
                $imageName = $name.'.'.$image->extension();  
                $image->move(public_path($folder), $imageName);

            }
            
            return Icon::whereid_icons($dataApplication->id_icons)->update([
                'extension' => $ex,
            ]);
    }
    //--> Imange Update
    function updateFotoCategory($request, $id, $folderIcons) {
        $dataApplication = Category::
                where('categories.id_applications', '=', $id)
                ->leftJoin('icons', 'categories.id_icons', '=', 'icons.id_icons')
                ->get()->first();
        
        $image_path = $dataApplication->folder.'/'.$dataApplication->name.'.'.$dataApplication->extension;
            if (File::exists($image_path)) {
                File::delete($image_path);
                $success = true;
            } else {
                $success = false;
            }
            if ($success = true) {
        
                $request->hasFile('file');
                $image = $request->file('file');    
                $folder = $folderIcons;
                $name = $dataApplication->name;
                $ex = $image->extension();
                $imageName = $name.'.'.$image->extension();  
                $image->move(public_path($folder), $imageName);

            }
            
            return Icon::whereid_icons($dataApplication->id_icons)->update([
                'extension' => $ex,
            ]);
    }
}
