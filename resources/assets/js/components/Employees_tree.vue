<template>
    <div class="employees">
        <div v-if="parent_component" class="employees__head row" >
            <div class="employees__head-item col-md-offset-6 col-md-3">Дата приема на работу</div>
            <div class="employees__head-item col-md-3">Размер зарплаты</div>
        </div>
        <div class="employee"
             @dragover.prevent @drop="drop" @dragstart="dragstart"
            draggable="true">
            <div class="employee-data row"
                 :class="{bold: hasChildren}"
                 @click="openNode">
                <span class="employee__node-open col-md-1 col-sm-1 fa" :class="[{'employee__node-open_no-child':!hasChildren&&alreadyLoaded},openIconClasses]"></span>
                <div class="employee__node-info col-md-5 col-sm-5">
                    <div class="employee__node-name">{{ model.name }}</div>
                    <div class="employee__node-position">{{ model.position}}</div>
                </div>
                <div class="employee__node-date number-data col-md-3 col-sm-3">{{ model.employmentDate}}</div>
                <div class="employee__node-salary number-data col-md-3 col-sm-3">{{ model.salary}}</div>
            </div>
            <div style="padding-left:50px" v-show="open" v-if="hasChildren">
                <item
                        class="item"
                        v-for="model in model.children"
                        :tree_data="model"
                        :key="model.id"
                >
                </item>
            </div>
        </div>
        <div v-if="parent_component" class="change-head__modal modal fade" id="change-head__modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Изменение начальника</h4>
                    </div>
                    <div class="modal-body">
                        <p>Вы действительно хотите изменить начальника у сотрудника «<span class="change-head__text change-head__employee"></span>» на «<span class="change-head__text change-head__target-head"></span>»?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success change-head__accept-button" @click="changeHead">Да</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Нет</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        props: {tree_data: Object},
        data() {
            return {
                model:this.tree_data,
                open: true,
                loaded:false,
                parent_component:(this._uid==1),
            }
        },
        computed: {
            hasChildren: function () {
                return this.model.children &&
                        this.model.children.length
            },
            alreadyLoaded: function () {//были ли уже подгружены подчиненные
                return this.hasChildren||this.loaded;
            },
            openIconClasses() {
                let isNodeOpen = this.open&&this.alreadyLoaded;
                return {
                    'fa-plus-circle': !isNodeOpen,
                    'fa-minus-circle': isNodeOpen
                }
            },
        },

        beforeCreate() {
            this.$options.components.item = require('./Employees_tree')
        },
        methods: {
            openNode() {
                if (this.hasChildren) {
                    this.open = !this.open
                }
                else if(!this.alreadyLoaded){
                    this.loadChildren();
                }
            },
            loadChildren() {
                this.$http.get('/employee/children/'+this.model.id)
                        .then((response) => {
                            Vue.set(this.model, 'children', []);
                            this.model.children.push(...response.data);
                            this.loaded=true;
                            this.open=true;
                        })
                        .catch((error) => {
                            console.log(error);
                        });
            },
            changeHead(){
                if(Vue.$employeeToChangeHead && Vue.$headToChangeHead) {
                    console.log(Vue.$employeeToChangeHead);
                    this.$http.get('/employee/change_head/' + Vue.$employeeToChangeHead + "/" + Vue.$headToChangeHead)
                        .then((response) => {
                        console.log(response.data);
                            location.reload();
                        })
                        .catch((error) => {
                            if(error.response && error.response.status===500){
                                alert("Кольцевая зависимость! Невозможно выбрать начальником сотрудника, подчиненного текущему пользователю!");
                            }else{
                                alert("Ошибка при изменении начальника. Попробуйте позже");
                                console.log(error);
                            }
                        });
                }
                $("#change-head__modal").modal('hide');
            },
            dragstart(e){
                e.stopPropagation();
                e.dataTransfer.effectAllowed = 'all';
                e.dataTransfer.dropEffect = 'copy';
                e.dataTransfer.setData('employee',JSON.stringify({
                    id:this.model.id,
                    name:this.model.name,
                    position:this.model.position,
                    employmentDate:this.model.employmentDate,
                    salary:this.model.salary,
                }));

            },
            drop(e){
                e.stopPropagation();
                let currentEmployee=JSON.parse(e.dataTransfer.getData('employee'));
                if(currentEmployee && currentEmployee.id!==this.model.id){
                    Vue.$employeeToChangeHead=currentEmployee.id;
                    Vue.$headToChangeHead=this.model.id;
                    $(".change-head__employee").text(currentEmployee.name);
                    $(".change-head__target-head").text(this.model.name);
                    $("#change-head__modal").modal('show');
                }

            }
        }
    }
</script>
