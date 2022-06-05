<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{

    protected $primaryKey = 'id_images';
    
    protected $fillable = [
        'id_applications','id_categories','folder','name','extension','status','position', 'url'
    ];

}
