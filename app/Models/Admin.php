<?php

namespace App\Models;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Admin extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'birthdate',
        'address',
        'gender',
        'image',
        'id'
    ];


    public static function getFillables(array $data = [])
    {
        $class = __CLASS__;
        $obj = new $class;

        $fillables = $obj->fillable;
        if (count($data) === 0) {
            return $fillables;
        }
        return Arr::only($data, $fillables);
    }


    public static function academicSets() {
        return AcademicSet::all();
    }

    public function picture() {
        return $this->user->picture();
    }

    public function user()
    {
        $this->hasOne(User::class);
    }

    public static function _create($data) {
        $obj = new Admin();
        $data = Arr::only($data, $obj->fillable);
        return self::create($data);
    }
}
