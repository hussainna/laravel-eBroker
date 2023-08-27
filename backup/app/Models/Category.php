<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    // public function categories_type() {
    //     return $this->hasMany(CategoryType::class,'categories_id','id')->with('type:id,type');
    // }

    protected $fillable = [
        'category',
        'image',
        'status',
        'sequence',
        'parameter_types'

    ];
    protected $hidden = [
        'updated_at'
    ];

    public function parameter()
    {
        return $this->hasMany(parameter::class);
    }

    public function getParameterTypeNamesAttribute()
    {
        $parameter_types = self::select('parameter_types')->where('categories.id', $this->id)->first();

        $tempRow = array();

        $parameterTypes = explode(',', $parameter_types->parameter_types);

        foreach ($parameterTypes as $row) {
            if ($row == 1) {
                $tempRow[]  = 'Carpet Area';
            }
            if ($row == 2) {
                $tempRow[]  = 'Built-Up Area';
            }
            if ($row == 3) {
                $tempRow[]  = 'Plot Area';
            }
            if ($row == 4) {
                $tempRow[]  = 'Hecta Area';
            }

            if ($row == 5) {
                $tempRow[]  = 'Acre';
            }
            if ($row == 6) {
                $tempRow[]  = 'House Type';
            }
            if ($row == 7) {
                $tempRow[]  = 'Furnished';
            }

            if ($row == 8) {
                $tempRow[]  = 'House No';
            }
            if ($row == 9) {
                $tempRow[]  = 'Survey No';
            }

            if ($row == 10) {
                $tempRow[]  = 'Plot No';
            }
        }
        return implode(',', $tempRow);
    }
    public function getImageAttribute($image)
    {
        return $image != "" ? url('') . config('global.IMG_PATH') . config('global.CATEGORY_IMG_PATH') . $image : '';
    }
}
