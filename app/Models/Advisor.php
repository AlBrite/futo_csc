<?php

namespace App\Models;

use App\Models\User;
use App\Models\Student;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Advisor extends Model
{
    use HasFactory;

    protected $table = 'advisors';

    protected $fillable = [
        'created_by',
        'admin_id',
        'birthdate',
        'address',
        'gender',
        'id',
        //'image',
        'set_id'
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

    // Define relationship 

    public function user()
    {
        return $this->hasOne(User::class, 'id');
    }


    public static function active() {
       

        if (!auth()->check()) {
            return null;
        }
        return self::where('id', auth()->id())->first();
    }
    

    public function classes()
    {
        return $this->hasMany(AcademicSet::class);
    }
    

    

    public function getInfo()
    {
        $values = array_map(fn ($item) => $this->{$item}, $this->fillable);
        return array_combine($this->fillabe, $values);
    }


    public function picture() {
        return $this->user->picture();
    }

    public function students() {
        $set = $this->class;
        return Student::where('set_id', '=', $set->id);
    }

    public function class() {
        return $this->hasOne(AcademicSet::class);
    }


    public static function _create($data) {
        $obj = new Advisor();
        $data = Arr::only($data, $obj->fillable);
        return self::create($data);
    }
}
