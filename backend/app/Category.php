<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    protected $primaryKey = 'id_categories';
    
    protected $fillable = [
        
        'id_user','id_icons','name_categories','id_applications'

    ];

}
