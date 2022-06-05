<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{

    protected $primaryKey = 'id_applications';
    
    protected $fillable = [

        'id_user','id_icons','name_applications',
        'package_applications','limit_applications',
        'key_applications'

    ];

    protected $guarded = [];

    public function ImageAssets()
    {
        return $this->hasMany(Image::class, 'id_applications');
    }
    
    public function CategoryAssets()
    {
        return $this->hasMany(Category::class, 'id_applications');
    }
}
