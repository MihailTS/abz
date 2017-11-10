<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Employees</title>
    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
    <script src="{{ mix('/js/app.js') }}"></script>
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
                    {{--@foreach($menuItems as $menuIndex=>$menuItem)
                        <li style="background-position: <?echo $menuOffsetY;$menuOffsetY-=141?>px 0" class="head-menu__list-item{{($menuItem[2])?" head-menu__list-item_special":""}}{{(isset($selectedIndex) && $menuIndex==$selectedIndex)?" head-menu__list-item_selected":""}}"><a href="{{$menuItem[1]}}">{{$menuItem[0]}}</a></li>
                    @endforeach--}}
                </ul>
            </div>
        </div>
    </nav>
</header>
<div class="container">
    <div class="employees row">
        @foreach($root as $parent_employee)
            <div class="employee">
                <div class="employee-content row">
                    <div class="employee__node-open col-md-1">+</div>
                    <div class="employee__name col-md-5">{{$parent_employee->name}}</div>
                    <div class="employee__position col-md-3">{{$parent_employee->position}}</div>
                </div>
                <div class="employee-children">
                    @foreach($parent_employee->children as $employee)
                        <div class="employee">
                            <div class="employee-content row">
                                <div class="employee__node-open col-md-1">+</div>
                                <div class="employee__name col-md-5">{{$employee->name}}</div>
                                <div class="employee__position col-md-3">{{$employee->position}}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>
</body>
</html>
