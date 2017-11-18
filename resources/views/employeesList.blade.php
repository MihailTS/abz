@extends('layouts.app')

@section('content')
    <div class="employees__head row" >
        <div class="employees__head-item employees__head-item_clickable col-md-3">ФИО</div>
        <div class="employees__head-item employees__head-item_clickable col-md-3">Должность</div>
        <div class="employees__head-item employees__head-item_clickable col-md-3">Дата приема на работу</div>
        <div class="employees__head-item employees__head-item_clickable col-md-3">Размер зарплаты</div>
        </div>
    @foreach($emplList as $empl)
    <div class="employee row">
            <div class="employee__node-name col-md-3">{{$empl->name}}</div>
            <div class="employee__node-position col-md-3">{{$empl->position}}</div>
            <div class="employee__node-date col-md-3">{{$empl->employmentDate}}</div>
            <div class="employee__node-salary col-md-3">{{$empl->salary}}</div>
    </div>
    @endforeach
    {{ $links }}

@endsection
