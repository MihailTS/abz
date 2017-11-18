@extends('layouts.app')

@section('content')

    <div id='app'>
        <empl-tree
                v-for="model in {{$jsonTree}}"
                :tree_data="model"
                :key="model.id"
        ></empl-tree>
    </div>
@endsection
