@extends('layouts.app')


@section('content')
    <div class="employees__head row" >
        <div class="employees__head-item col-md-offset-6 col-md-3">Дата приема на работу</div>
        <div class="employees__head-item col-md-3">Размер зарплаты</div>
    </div>
    <div id='app'>
        <empl-tree
                v-for="model in {{$jsonTree}}"
                :tree_data="model"
                :key="model.id"
        ></empl-tree>
    </div>
@endsection
