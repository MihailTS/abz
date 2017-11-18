<template>
    <div class="employees">
        <div class="employee"
             @dragover.prevent @drop="drop" @dragstart="dragstart"
            draggable="true">
            <div class="employee-data row"
                 :class="{bold: hasChildren}"
                 @click="openNode">
                <span class="employee__node-open col-md-1 fa" :class="[{'employee__node-open_no-child':!hasChildren&&alreadyLoaded},openIconClasses]"></span>
                <div class="employee__node-info col-md-5">
                    <div class="employee__node-name">{{ model.name }}</div>
                    <div class="employee__node-position">{{ model.position}}</div>
                </div>
                <div class="employee__node-date col-md-3">{{ model.employmentDate}}</div>
                <div class="employee__node-salary col-md-3">{{ model.salary}}</div>
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
            dragstart(e){
                e.stopPropagation();
                e.dataTransfer.effectAllowed = 'all';
                e.dataTransfer.dropEffect = 'copy';
                e.dataTransfer.setData('data',JSON.stringify({
                    id:this.model.id,
                    name:this.model.name,
                    position:this.model.position,
                    employmentDate:this.model.employmentDate,
                    salary:this.model.salary,
                }));
                console.log(e.dataTransfer.getData('data'));

            },
            drop(e){
                e.stopPropagation();
               console.log(this.model.name);
            }
        }
    }
</script>
