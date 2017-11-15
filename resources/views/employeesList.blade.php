<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Employees</title>
    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
    <!-- Styles -->
    <style>

    </style>
</head>
<body>
<header>
    <nav role="navigation" class="navbar navbar-default ">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-main">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand visible-xs" href="#">Меню</a>
            </div>
            <div class="collapse navbar-collapse" id="navbar-main">
                <ul class="head-menu__list nav navbar-nav">
                    <li class="head-menu__list-item {{(isset($selectedIndex) && $menuIndex==$selectedIndex)?"head-menu__list-item_selected":""}}"><a href="/">Главная</a></li>
                </ul>
            </div>
        </div>
    </nav>
</header>

<div class="container">
    <div class="employees-list__head row" >
        <div class="empl-list__head-item col-md-3">ФИО</div>
        <div class="empl-list__head-item col-md-3">Должность</div>
        <div class="empl-list__head-item col-md-3">Дата приема на работу</div>
        <div class="empl-list__head-item col-md-3">Размер зарплаты</div>
    </div>
    <div class="empl-list-item row">
        @foreach($employeesData as $empl)
            <div class="empl-list-item__name col-md-3">{{$empl->name}}</div>
            <div class="empl-list-item__position col-md-3">{{$empl->position}}</div>
            <div class="empl-list-item__date col-md-3">{{$empl->employmentDate}}</div>
            <div class="empl-list-item__salary col-md-3">{{$empl->salary}}</div>
        @endforeach
    </div>
    {{ $employeesData->links() }}

</div>
<script src="{{ mix('/js/app.js') }}"></script>
</body>
</html>
