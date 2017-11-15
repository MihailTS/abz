<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employee;

class EmployeeController extends Controller
{
    const DEFAULT_LEVELS=2;
    public function index(){//главная страница
        $jsonTree=Employee::with('children')->whereNull('head_id')->select('id','name','position',
            'employmentDate','salary')->get()->toJson();
        return view('employees',["jsonTree"=>$jsonTree]);
    }

    public function emplList(Request $request){//страница списка
        $employeesData=Employee::select('id','name','position',
            'employmentDate','salary')->paginate(40);
        return view('employeesList',["employeesData"=>$employeesData]);
    }

    public function getChildren($empl_id){
        $childrenEmployees=Employee::find($empl_id)->children()->with('children')->get()->toJson();
        return $childrenEmployees;
    }

}
