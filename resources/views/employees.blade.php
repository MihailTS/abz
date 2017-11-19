@extends('layouts.app')

@section('content')

    <empl-tree
            v-for="model in {{$jsonTree}}"
            :tree_data="model"
            :key="model.id"
    ></empl-tree>
@endsection
