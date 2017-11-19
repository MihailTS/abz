@extends('layouts.app')

@section('content')
    <div class="employees__head row" >
        <div data-sort="name" class="employees__head-item employees__head-item_clickable col-md-3">ФИО</div>
        <div data-sort="position" class="employees__head-item employees__head-item_clickable col-md-3">Должность</div>
        <div data-sort="employmentDate" class="employees__head-item employees__head-item_clickable col-md-3">Дата приема на работу</div>
        <div data-sort="salary" class="employees__head-item employees__head-item_clickable col-md-3">Размер зарплаты</div>
        </div>
    <div class="employees">
        @foreach($emplList as $empl)
        <div class="employee row">
                <div class="employee__node-name col-md-3">{{$empl->name}}</div>
                <div class="employee__node-position col-md-3">{{$empl->position}}</div>
                <div class="employee__node-date col-md-3">{{$empl->employmentDate}}</div>
                <div class="employee__node-salary col-md-3">{{$empl->salary}}</div>
        </div>
        @endforeach
    </div>
    <ul class="employees__pagination pagination">

    </ul>
@endsection
@section('scripts')
    <script type="text/javascript">
        class CustomPagination{
            constructor(currentPage,lastPage,sort,order){
                this.showPagination=(lastPage>1);
                if(this.showPagination){
                    this.sort=sort;
                    this.order=order;
                    this.currentPage=currentPage;
                    this.lastPage=lastPage;

                    this.paginationArray=[1,2];

                    this.addPage(currentPage-1);
                    this.addPage(currentPage);
                    this.addPage(currentPage+1);

                    this.addPage(lastPage-1);
                    this.addPage(lastPage);
                }
            }
            addPage(page){
                if(page>0 && page<=this.lastPage && !this.paginationArray.includes(page)){
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
                    container.html(paginationHtml);
                }
            }
        }
        let paginationBlock=$(".employees__pagination");
        let emplPagination=new CustomPagination({!!$emplList->currentPage()!!},{!!$emplList->lastPage()!!},"{!! $sortLinks['sort'] !!}","{!! $sortLinks['order'] !!}");
        emplPagination.render(paginationBlock);
        $(".employees__head-item").click(function(){
            let currentSortType= emplPagination.sort;
            let currentSortOrder= emplPagination.order;
            let newSortType=$(this).data('sort');
            let newSortOrder='asc';
            let page= emplPagination.currentPage;
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
                data:{page:page,sort:newSortType,order:newSortOrder},
                dataType:"json",
                success:(data)=>{
                    let employeesHtml="";
                    data.forEach((i)=>{
                        employeesHtml+="<div class='employee row'><div class='employee__node-name col-md-3'>"+i.name+"</div>" +
                            "<div class='employee__node-position col-md-3'>"+i.position+"</div>" +
                            "<div class='employee__node-date col-md-3'>"+i.employmentDate+"</div>" +
                            "<div class='employee__node-salary col-md-3'>"+i.salary+"</div></div>";
                    });
                    $(".employees").html(employeesHtml);
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

    </script>

@endsection