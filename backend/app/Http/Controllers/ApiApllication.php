<?php

namespace App\Http\Controllers;

use App\Application;
use App\Category;
use App\Icon;
use App\Image;
use App\Setting;
use App\User;

use Illuminate\Http\Request;

class ApiApllication extends Controller
{
    //--> Show Application Image All
    public function ShowImangeAll($key) {
        $id = Application::where('applications.key_applications', '=', $key)
            ->get()->first();
        
        $data = Image::where('images.id_applications', '=', $id->id_applications)
            ->select(
                'images.id_images as id',
                'images.id_applications',
                'images.url as download_url'
            )
            ->paginate(7);
        return $data->items();
    }

    //--> Show Category Image
    public function ShowCategoryAll($key) {
        $id = Application::where('applications.key_applications', '=', $key)
            ->get()->first();

        $data = Category::where('categories.id_applications', '=', $id->id_applications)
            ->leftJoin('icons', 'categories.id_icons', '=', 'icons.id_icons')
            ->select(
                'categories.id_categories',
                'categories.id_applications',
                'categories.name_categories',
                'icons.url'
            )
            ->get();

        return $data;
    }

    //--> Show Image By Category ID
    public function ShowImageByIdCategory($id) {
        $data = Image::where('images.id_categories', '=', $id)
            ->select(
                'images.id_images as id',
                'images.id_applications',
                'images.url as download_url'
            )
            ->paginate(7);

        return $data->items();
    }

}
