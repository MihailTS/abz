@extends('layouts.app')

@section('content')
    @if(Session::has('success_msg'))
        <div class="alert alert-success">{{ Session::get('success_msg') }}</div>
    @endif
    <div class="row">
        <div class="col-md-6">
            <div id="employees__search">
                <form class="employees__search-form input-group col-md-12" method="get" action="">
                    <input required type="text" name="q" class="employees__search-query form-control input-lg" placeholder="Поиск по сотрудникам" value="{{$searchQuery}}"/>
                    <span class="input-group-btn">
                        <button class="btn btn-lg employees__search-button" type="submit">
                            <i class="glyphicon glyphicon-search"></i>
                        </button>
                    </span>
                </form>
            </div>
        </div>
    </div>
    <br>
    <div class="employees__add">
        <a class="btn btn-success" href="{{route('employees.create')}}">Добавить сотрудника</a>
    </div>
    <hr>
    <div class="employees__head row" >
        <div class="employees__head-item col-md-1 col-sm-1"></div>
        <div data-sort="name" class="employees__head-item employees__head-item_clickable col-md-3 col-sm-3 @if($sortLinks['sort']=="name"){{($sortLinks['order']=="desc")?"employees__head-item_sort-desc":"employees__head-item_sort-asc"}}@endif">ФИО</div>
        <div data-sort="position" class="employees__head-item employees__head-item_clickable col-md-3 col-sm-3 @if($sortLinks['sort']=="position"){{($sortLinks['order']=="desc")?"employees__head-item_sort-desc":"employees__head-item_sort-asc"}}@endif">Должность</div>
        <div data-sort="employmentDate" class="employees__head-item employees__head-item_clickable col-md-2 col-sm-2 @if($sortLinks['sort']=="employmentDate"){{($sortLinks['order']=="desc")?"employees__head-item_sort-desc":"employees__head-item_sort-asc"}}@endif">Дата приема на работу</div>
        <div data-sort="salary" class="employees__head-item employees__head-item_clickable col-md-2 col-sm-2 @if($sortLinks['sort']=="salary"){{($sortLinks['order']=="desc")?"employees__head-item_sort-desc":"employees__head-item_sort-asc"}}@endif">Размер зарплаты</div>
        </div>
    <div class="employees">
        @forelse($emplList as $empl)
        <div class="employee row">
                <div class="employee__node-image col-md-1 col-sm-1">
                    @if($empl->thumbnail)
                        <img src="{{$empl->thumbnail}}" height="50px">
                    @endif
                </div>
                <a href="{{route('employees.show',$empl->id)}}"><div class="employee__node-item employee__node-name col-md-3 col-sm-3">{{$empl->name}}</div></a>
                <div class="employee__node-item employee__node-position col-md-3 col-sm-3">{{$empl->position}}</div>
                <div class="employee__node-item employee__node-date number-data col-md-2 col-sm-2">{{$empl->employmentDate}}</div>
                <div class="employee__node-item employee__node-salary number-data col-md-2 col-sm-2">{{$empl->salary}}</div>
        </div>
        @empty
                <div>По данному запросу сотрудники не найдены</div>
        @endforelse
    </div>
    <ul class="employees__pagination pagination">

    </ul>
@endsection
@section('scripts')
    <script type="text/javascript">

        class CustomPagination{
            constructor(currentPage,lastPage,sort,order,search){
                this.showPagination=(lastPage>1);

                this.currentPage=parseInt(currentPage);
                this.lastPage=parseInt(lastPage);
                this.sort=sort;
                this.order=order;
                this.search=search;
                if(this.showPagination){
                    this.paginationArray=[1,2];

                    this.addPage(this.currentPage-1);
                    this.addPage(this.currentPage);
                    this.addPage(this.currentPage+1);

                    this.addPage(this.lastPage-1);
                    this.addPage(this.lastPage);
                }
            }
            addPage(page){
                if(page>0 && page<=this.lastPage && $.inArray(page, this.paginationArray)===-1){
                    this.paginationArray.push(page);
                }
            }
            leftArrowHtml(){
                let leftArrow="";
                if(this.currentPage===1){
                    leftArrow+="<li class='disabled'><span>«</span>";
                }else{
                    leftArrow+="<li><a href='"+this.pageLinkHtml(this.currentPage-1)+"' rel='prev'>«</a></li>";
                }
                return leftArrow;
            }
            rightArrowHtml(){
                let rightArrow="";
                if(this.currentPage===this.lastPage){
                    rightArrow+="<li class='disabled'><span>»</span>";
                }else{
                    rightArrow+="<li><a href='"+this.pageLinkHtml(this.currentPage+1)+"' rel='next'>»</a></li>";
                }
                return rightArrow;
            }
            pageLinkHtml(page){
                let pageLink="?page=" + page;
                if(this.sort && this.sort!=='id'){
                    pageLink+="&sort="+this.sort;
                }
                if(this.order && this.order!=='asc'){
                    pageLink+="&order="+this.order;
                }
                if(this.search){
                    pageLink+="&q="+this.search;
                }
                return pageLink;
            }
            render(container){
                let paginationHtml="";
                if(this.showPagination){
                    paginationHtml=this.leftArrowHtml();
                    let prevPage=0;
                    let pageLink="";
                    for(let i=0; i<this.paginationArray.length;i++){
                        pageLink="";
                        if(this.paginationArray[i]===this.currentPage){
                            pageLink="<li class='active'><span>"+this.paginationArray[i]+"</span></li>";
                        }
                        else {
                            if (this.paginationArray[i] !== prevPage + 1) {
                                pageLink = "<li class='disabled'><span>...</span></li>";
                            }
                            pageLink += "<li><a href='"+this.pageLinkHtml(this.paginationArray[i])+"'>" + this.paginationArray[i] + "</a></li>";
                        }
                        paginationHtml+=pageLink;
                        prevPage=this.paginationArray[i];
                    }
                    paginationHtml+=this.rightArrowHtml();
                }
                if(this.lastPage===0){
                    paginationHtml+="<div>По данному запросу сотрудники не найдены</div>";
                }
                container.html(paginationHtml);
            }
        }
        let fillEmployeesList=function(data){
            let employeesHtml="";
            if(typeof data.forEach === "function"){
                data.forEach((i)=>{
                    employeesHtml+="<div class='employee row'>" +
                        "<div class='employee__node-image col-md-1 col-sm-1'>"+
                        ((i.thumbnail)?"<img src='"+i.thumbnail+"' height='48px'>":"")+
                        "</div><a href='{{route('employees.index')}}/"+i.id+"'>" +
                        "<div class='employee__node-item employee__node-name col-md-3 col-sm-3'>"+i.name+"</div></a>" +
                        "<div class='employee__node-item employee__node-position col-md-3 col-sm-3'>"+i.position+"</div>" +
                        "<div class='employee__node-item employee__node-date number-data col-md-2 col-sm-2'>"+i.employmentDate+"</div>" +
                        "<div class='employee__node-item employee__node-salary number-data col-md-2 col-sm-2'>"+i.salary+"</div></div>";
                });
            }
            $(".employees").html(employeesHtml);
        };
        let paginationBlock=$(".employees__pagination");
        let emplPagination=new CustomPagination(
                {!!$emplList->currentPage()!!},
                {!!$emplList->lastPage()!!},
                "{!! $sortLinks['sort'] !!}",
                "{!! $sortLinks['order'] !!}",
                "{!! $searchQuery !!}",
        );
        emplPagination.render(paginationBlock);
        $(".employees__head-item").click(function(e){
            e.preventDefault();
            let page = emplPagination.currentPage;
            let search = emplPagination.search;
            let currentSortType = emplPagination.sort;
            let currentSortOrder = emplPagination.order;
            let newSortType=$(this).data('sort');
            let newSortOrder='asc';
            if(currentSortType===newSortType){
                if(currentSortOrder==='asc'){
                    newSortOrder='desc';
                }else{
                    newSortOrder='asc';
                }
            }else{
                newSortOrder=currentSortOrder;
            }
            $.get({
                url:"/list/_sort",
                data:{page:page,sort:newSortType,order:newSortOrder,q:search},
                dataType:"json",
                success:(data)=>{
                    fillEmployeesList(data.items);
                    emplPagination.sort=newSortType;
                    emplPagination.order=newSortOrder;
                    let headItems=$(".employees__head-item");
                    headItems.removeClass("employees__head-item_sort-asc");
                    headItems.removeClass("employees__head-item_sort-desc");
                    if(newSortOrder==="desc"){
                        $(this).addClass("employees__head-item_sort-desc");
                    }else{
                        $(this).addClass("employees__head-item_sort-asc");
                    }
                    emplPagination.render(paginationBlock);
                }
            });
        });
        $(".employees__search-button").click(function(e){
            e.preventDefault();
            let page=1;
            let sort=emplPagination.sort;
            let order=emplPagination.order;
            let search=$(".employees__search-query").val();

            $.get({
                url:"/list/_sort",
                data:{page:page,sort:sort,order:order,q:search},
                dataType:"json",
                success:(data)=>{
                    fillEmployeesList(data.items);
                    emplPagination=new CustomPagination(data.pagination.currentPage,data.pagination.lastPage,
                        sort,order,search);
                    emplPagination.render(paginationBlock);
                }
            });
        });
    </script>

@endsection