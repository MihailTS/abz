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
<script>


</script>
<style>.employee__node-open_no-child{
        visibility: hidden;
    }
    .employee__node-position{
        font-style:italic;
        font-size:0.85em;
    }
    .employees__head-item{
        font-weight: bold;
        font-size:1.1em;
    }
    .employees__head{
        border-bottom:1px solid #555555;
        margin-bottom:20px;
    }
    .employee__node-date,.employee__node-salary{
        font-family: "Inconsolata", "Fira Mono", "Source Code Pro", Monaco, Consolas, "Lucida Console", monospace;
    }
</style>
<div class="container">
    <div class="employees__head row" >
        <div class="employees__head-item col-md-offset-6 col-md-3">Дата приема на работу</div>
        <div class="employees__head-item col-md-3">Размер зарплаты</div>
    </div>
    <div id='app'>
        <empl-tree
                v-for="model in treeData"
                :model="model"
                :key="model.id"
        ></empl-tree>
    </div>
</div>
<script>let tree = {!!$jsonTree!!};
</script>
<script src="{{ mix('/js/app.js') }}"></script>
</body>
</html>
