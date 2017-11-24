@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <a href="{{ route('employees.index') }}" class="btn btn-info">Назад</a>
            <div>
                <h2>{{ $employee->name }}</h2>
            </div>
        </div>
    </div>
    <div class="row">
        @if($employee->avatar)
        <div class="col-md-6 cold-sm-12">
            <img width="100%" src="{{$employee->avatar}}" alt="">
        </div>
        @endif
        <div class="row col-md-6 col-sm-12">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Должность:</strong>
                    {{ $employee->position }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Дата приема на работу:</strong>
                    <span class="number-data">{{$employee->employmentDate }}</span>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Зарплата:</strong>
                    <span class="number-data">{{ $employee->salary }}</span>
                </div>
            </div>
            @if($employee->head)
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Начальник:</strong>
                    <a href="{{route('employees.show',$employee->head->id)}}">{{ $employee->head->name }}</a>
                </div>
            </div>
            @endif
        </div>
    </div>
    <hr>
    <div class="row">
       <a class="btn btn-info" href="{{route("employees.edit",$employee->id)}}">Редактировать</a>
       <form class="btn" action="{{ route('employees.destroy', $employee->id) }}" method="POST">
           {{ csrf_field() }}
           {{ method_field('DELETE') }}
           <button type="submit" class="btn btn-danger">Удалить</button>
       </form>
    </div>
@endsection