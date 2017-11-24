@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach()
                </div>
            @endif
            <div class="panel panel-default">
                <div class="panel-heading" style="overflow: hidden;">
                    <h4 class="pull-left">Добавить сотрудника</h4>
                    <a href="{{ route('employees.index') }}" class="btn btn-info pull-right">Назад</a>
                </div>
                <div class="panel-body">
                    <form enctype="multipart/form-data" action="{{ route('employees.store') }}" method="POST" class="form-horizontal">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label class="control-label col-sm-3" >Фотография</label>
                            <div class="col-sm-9">
                                <input type="file" accept="image/jpg,image/jpeg,image/png" name="avatar" id="avatar" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-3" >ФИО</label>
                            <div class="col-sm-9">
                                <input type="text" name="name" id="name" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-3" >Должность</label>
                            <div class="col-sm-9">
                                <input type="text" name="position" id="position" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-3" >Зарплата</label>
                            <div class="col-sm-9">
                                <input type="number" step="0.01" name="salary" id="salary" class="number-data form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-3" >Дата принятия на работу</label>
                            <div class="col-sm-9">
                                <input type="date" name="employmentDate" id="employmentDate" class="form-control" value="{{\Carbon\Carbon::now()->format("Y-m-d")}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-3" >ID начальника</label>
                            <div class="col-sm-9">
                                <input type="number" name="head_id" id="head_id" class="form-control number-data">
                                <div class="head__error alert alert-danger"><p>Сотрудник с таким ID отсутствует!</p></div>
                                <div class="head__found alert alert-success"><p id="head__found-name"></p></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-9">
                                <input type="submit" class="btn btn-default" value="Добавить сотрудника" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section("scripts")
    <script>
        let throttle =function (func, wait) {
            return function() {
                let that = this,
                    args = [].slice(arguments);

                clearTimeout(func._throttleTimeout);

                func._throttleTimeout = setTimeout(function() {
                    func.apply(that, args);
                }, wait);
            };
        };
        let foundedHeadBlock=$(".head__found");
        let errorHeadBlock=$(".head__error");
        let foundedHeadNameBlock=$("#head__found-name");
        let showHeadName=function(id){
            {
               foundedHeadNameBlock.text("");
               foundedHeadBlock.hide();
               errorHeadBlock.hide();

                if(id){
                    $.get({
                        url:"/employee/_headname/"+id,
                        dataType:"json",
                        success:(data)=>{
                            if(data.status==='success'){
                                foundedHeadNameBlock.text(data.name);
                                foundedHeadBlock.show();
                            }else{
                                errorHeadBlock.show();
                            }
                        }
                    });
                }
            }
        };
        $(function(){
            $("#head_id").keyup(throttle(function(){showHeadName($(this).val())}, 500 ));
        });
    </script>
@endsection