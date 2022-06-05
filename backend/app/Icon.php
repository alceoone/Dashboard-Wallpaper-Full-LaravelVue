<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Icon extends Model
{

    protected $primaryKey = 'id_icons';
    
    protected $fillable = [

        'folder','name','extension','url'
       
    ];

}
