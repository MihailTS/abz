<?php

namespace App;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    public static function boot() {
        parent::boot();


        self::deleting(function ($value) {
            Employee::where('head_id',$value->id)->update(['head_id' => $value->head_id]);
        });
    }


    public function head()
    {
        return $this->belongsTo(Employee::class);
    }
}
