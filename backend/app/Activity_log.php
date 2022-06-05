<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity_log extends Model
{

    protected $primaryKey = 'id';
    
    protected $fillable = [
        
        'id_user','note'   

    ];

}
