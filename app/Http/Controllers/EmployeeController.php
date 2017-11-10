<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employee;

class EmployeeController extends Controller
{
    const DEFAULT_LEVELS=2;
    public function index(){
        $root=Employee::with('children')->whereNull('head_id')->get();
        //$secondLevel=$root[0]->children;
        return view('employees',["root"=>$root]);
    }
}
