<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table='categories';


    protected $fillable =
        [
            'name_ar',
            'name_en',
            'created_at',
            'update_at',
            'active'
        ];

    public function scopeSelection($query)
    {
        return $query->select('id', 'name_' . app()->getLocale() . ' as name');
    }
}
