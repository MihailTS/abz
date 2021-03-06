<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employee;
use Illuminate\Support\Facades\Session;
use Intervention\Image;

class EmployeeController extends Controller
{
    const THUMB_WIDTH=200;
    const PAGINATION_ON_PAGE=40;
    private static $requiredFields = [
        'name' => 'required',
        'position' => 'required',
        'head' => 'accepted',
        'salary' => 'required|numeric',
        'employmentDate' => 'required|date',
        'avatar'=>'mimes:jpeg,jpg,png'
    ];
    private static $requiredMessages=[
        'name.required' => "Введите имя сотрудника!",
        'position.required' => "Введите должность сотрудника!",
        'salary.required' => "Введите зарплату сотрудника!",
        'salary.numeric' => "Некорректный формат зарплаты!",
        'employmentDate.date' => "Некорректный формат даты принятия на работу!",
        'employmentDate.required' => "Введите дату приема на работу сотрудника!",
        'head.accepted' => 'Некорректный id начальника!',
        'not_supreme.accepted' => 'Начальник не может быть подчиненным(как прямым, так и косвенным)!'
    ];
    public function tree(){//главная страница
        $jsonTree=Employee::with('children')->whereNull('head_id')->select('id','name','position',
            'employmentDate','salary')->get()->toJson();
        return view('employees',["jsonTree"=>$jsonTree]);
    }
    public function index(Request $request){
        return $this->emplList($request);
    }
    public function show($id){
        $empl = Employee::with('head')->find($id);
        if($empl){
            return view('employee/show', ['employee' => $empl]);
        }else{
            return abort(404);
        }
    }

    public function create(){
        return view('employee/create');
    }
    public static function saveImage($image){
        if($image){
            $result=[];
            $originalImageSize=getimagesize($image->getRealPath());
            $ratio=$originalImageSize[1]/$originalImageSize[0];
            $imageHashName=$image->hashName("/avatar");
            $thumbHashName=$image->hashName("/thumbnails");
            $heightWithSaveRatioThumb=intval(self::THUMB_WIDTH*$ratio);
            $avatar=\Image::make($image);
            $avatar->save(public_path($imageHashName));
            $thumb=\Image::make($image)->fit(self::THUMB_WIDTH,$heightWithSaveRatioThumb);
            $thumb->save(public_path($thumbHashName));
            $result['avatar']=$imageHashName;
            $result['thumbnail']=$thumbHashName;
            return $result;
        }
        return false;
    }
    public function store(Request $request){
        $request['head']=boolval(Employee::find($request->head_id));
        $this->validate($request, self::$requiredFields, self::$requiredMessages);
        $image = $request->file('avatar');
        $employeeData = $request->all();

        if($image){
            $imagesPath=self::saveImage($image);
            if($imagesPath){
                $employeeData['avatar']=$imagesPath['avatar'];
                $employeeData['thumbnail']=$imagesPath['thumbnail'];
            }
        }
        Employee::create($employeeData);
        Session::flash('success_msg', 'Сотрудник добавлен!');

        return redirect()->route('employees.index');
    }

    public function edit($id){
        $empl = Employee::with('head')->find($id);
        if($empl){
            return view('employee/edit', ['employee' => $empl]);
        }else{
            return abort(404);
        }
    }

    public function update($id, Request $request){
        $empl=Employee::find($id);

        if($empl) {
            $employeeData = $request->all();
            $updateValidationFields=self::$requiredFields;
            $image = $request->file('avatar');
            if(!empty($employeeData['delete-avatar'])){
                $employeeData['avatar']=null;
                $employeeData['thumbnail']=null;
            }
            elseif(!$image){
                unset($employeeData['avatar']);
            }
            else{
                $imagesPath=self::saveImage($image);
                $employeeData['avatar']=$imagesPath['avatar'];
                $employeeData['thumbnail']=$imagesPath['thumbnail'];
            }
            if(!empty($employeeData['head_id'])){
                $head=Employee::find($employeeData['head_id']);
                $request['head']=$employeeData['head_id']!=$empl->id && boolval($head);
                if($head){
                    $request['not_supreme'] =  !$empl->isSupreme($head);
                    $updateValidationFields['not_supreme']='accepted';
                }
            }
            if(empty($empl->head_id)){//если у сотрудника не был указан начальник(президент), то он не обязателен для него
                unset($updateValidationFields['head']);
            }

            $this->validate($request, $updateValidationFields, self::$requiredMessages);

            $empl->update($employeeData);
            Session::flash('success_msg', 'Информация о сотруднике обновлена!');
        }

        return redirect()->route('employees.show',$id);
    }

    public function destroy($id){
        $empl=Employee::find($id);
        if($empl){
            $empl->delete();
            Session::flash('success_msg', 'Сотрудник удален!');
        }
        return redirect()->route('employees.index');
    }
    public function emplList(Request $request){//страница списка
        $sortField='id';
        $sortOrder='asc';
        $fields=['id','name','position','employmentDate','salary','thumbnail'];
        $employeesData=Employee::select($fields);
        $sortLinks=[];
        $searchQuery=$request->q;
        if(!empty($searchQuery)){
            $employeesData=$employeesData->where(
                'name', 'LIKE', '%'.$searchQuery.'%')->orWhere(
                'position', 'LIKE', '%' . $searchQuery . '%')->orWhere(
                'employmentDate', 'LIKE', '%' . $searchQuery . '%')->orWhere(
                'salary', 'LIKE', '%' . $searchQuery . '%');
        }
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
        $employeesData=$employeesData->paginate(self::PAGINATION_ON_PAGE);
        return view('employeesList',["emplList"=>$employeesData,"sortLinks"=>$sortLinks,"searchQuery"=>$searchQuery]);
    }

    public function getHeadNameByID($id){
        $result=['status'=>'success'];
        $empl=Employee::select('name')->find($id);
        if($empl){
            $result['name']=$empl->name;
        }else{
            $result['status']='error';
        }
        return json_encode($result);
    }
    public function getSortedAjax(Request $request){
        $sortField='id';
        $sortOrder='asc';
        $page=$request->page;
        if(!is_numeric($page) || $page<=0){
            $page=1;
        }
        $fields=['id','name','position','employmentDate','salary','thumbnail'];
        $employeesData=Employee::select($fields);
        $searchQuery=$request->q;
        if(!empty($searchQuery)){
            $employeesData=$employeesData->where(
                'name', 'LIKE', '%'.$searchQuery.'%')->orWhere(
                'position', 'LIKE', '%' . $searchQuery . '%')->orWhere(
                'employmentDate', 'LIKE', '%' . $searchQuery . '%')->orWhere(
                'salary', 'LIKE', '%' . $searchQuery . '%');
        }
        if(!empty($request->sort) && in_array($request->sort,$fields)){
            $sortField=$request->sort;
        }
        if(!empty($request->order) && ($request->order=='asc' || $request->order=='desc')){
            $sortOrder=$request->order;
        }
        $employeesData=$employeesData->orderBy($sortField,$sortOrder);
        $employeesCount=$employeesData->count();
        if($employeesCount>0){
            $lastPage=floor($employeesCount/self::PAGINATION_ON_PAGE);
            if($employeesCount%self::PAGINATION_ON_PAGE>0){
                $lastPage++;
            }
            $employeesData=$employeesData->skip($page*self::PAGINATION_ON_PAGE-self::PAGINATION_ON_PAGE)
                ->take(self::PAGINATION_ON_PAGE)->get();
        }else{
            $lastPage=0;
        }
        $result['items']=$employeesData;
        $result['pagination']['lastPage']=$lastPage;
        $result['pagination']['currentPage']=(int)$page;
        $result['search']=$searchQuery;
        return json_encode($result);

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
