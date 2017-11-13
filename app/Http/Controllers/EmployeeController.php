<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employee;

class EmployeeController extends Controller
{
    const DEFAULT_LEVELS=2;
    public function index(){
        $jsonTree=Employee::with('children')->whereNull('head_id')->select('id','name','position',
            'employmentDate','salary')->get()->toJson();
        return view('employees',["jsonTree"=>$jsonTree]);
    }

    public function getChildren($empl_id){
        $childrenEmployees=Employee::find($empl_id)->children()->with('children')->get()->toJson();
        return $childrenEmployees;
    }
}
