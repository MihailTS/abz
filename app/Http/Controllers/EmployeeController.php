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
        $sortField='id';
        $sortOrder='asc';
        $fields=['id','name','position','employmentDate','salary'];
        $employeesData=Employee::select($fields);
        $sortLinks=[];
        if(!empty($request->sort) && in_array($request->sort,$fields)){
            $sortField=$request->sort;
            $sortLinks['sort']=$sortField;
        }
        if(!empty($request->order) && ($request->order=='asc' || $request->order=='desc')){
            $sortOrder=$request->order;
            $sortLinks['order']=$sortOrder;
        }
        $employeesData=$employeesData->orderBy($sortField,$sortOrder);
        $employeesData=$employeesData->paginate(40);

        $links = $employeesData->appends($sortLinks)->links();
        return view('employeesList',["emplList"=>$employeesData,"links"=>$links]);
    }

    public function getSorted(Request $request){
        //if($request->get('sort'))
    }
    public function getChildren($empl_id){
        $childrenEmployees=Employee::find($empl_id)->children()->with('children')->get()->toJson();
        return $childrenEmployees;
    }

}
