<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employee;
use DB;

class EmployeeController extends Controller
{
    const PAGINATION_ON_PAGE=40;
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

        $sortLinks['sort']=$sortField;
        if(!empty($request->order) && ($request->order=='asc' || $request->order=='desc')){
            $sortOrder=$request->order;
            $sortLinks['order']=$sortOrder;
        }
        $sortLinks['order']=$sortOrder;
        $employeesData=$employeesData->orderBy($sortField,$sortOrder);
        $employeesData=$employeesData->paginate($this::PAGINATION_ON_PAGE);
        return view('employeesList',["emplList"=>$employeesData,"sortLinks"=>$sortLinks]);
    }

    public function getSortedAjax(Request $request){
        $sortField='id';
        $sortOrder='asc';
        $page=$request->page;
        if(!is_numeric($page) || $page<=0){
            $page=1;
        }
        $fields=['id','name','position','employmentDate','salary'];
        if(!empty($request->sort) && in_array($request->sort,$fields)){
            $sortField=$request->sort;
        }
        if(!empty($request->order) && ($request->order=='asc' || $request->order=='desc')){
            $sortOrder=$request->order;
        }
        $employeesData=Employee::select($fields)->skip($page*$this::PAGINATION_ON_PAGE-$this::PAGINATION_ON_PAGE)
            ->take($this::PAGINATION_ON_PAGE)->orderBy($sortField,$sortOrder)->get()->toJson();
        return $employeesData;

    }
    public function getChildren($id){
        $childrenEmployees=Employee::find($id)->children()->with('children')->get()->toJson();
        return $childrenEmployees;
    }
    public function changeHead($id,$head){
        $employee=Employee::find($id);
        if(!empty($employee)){
            try{
                if(!($employee->changeHead($head))){
                    return response('no employee', 500);
                }
            }
            catch(\Exception $e){
                return response('parent', 500);
            }
        }
    }

}
