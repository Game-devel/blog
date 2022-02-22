<template>
    <div>
        <header class="uk-content-header uk-background-default">
            <div class="title">
                <h2>Блоги</h2>
                <div class="uk-float-right">
                    <a href="/admin/blogs/create/" class="uk-button uk-button-primary uk-border-rounded">Создать
                        блог</a>
                    <!-- <a href="/admin/blogs/add/template/" class="uk-button uk-button-secondary uk-border-rounded">Создать
                        из шаблона </a> -->
                </div>
            </div>
            <div class=" uk-child-width-1-2@m uk-grid">
                <div class="uk-margin uk-margin-small-top">
                    <label class="uk-form-label" for="header">Найти:</label>
                    <div class="uk-form-controls">
                        <div class="uk-inline uk-width-1-1">
                            <button class="uk-button-primary uk-form-icon uk-form-icon-flip uk-margin-remove"
                                    uk-icon="icon: search" @click="getSearch"></button>
                            <input v-model="keyword" @keyup="getSearch" class="uk-input" id="header" type="text"
                                   placeholder="Найти" autocomplete="off">
                        </div>
                    </div>
                </div>
                <div class="uk-margin uk-margin-small-top">
                    <label for="status" class="uk-form-label">Рубрика:</label>
                    <div class="uk-form-controls">
                        <select class="uk-select uk-width-1-2@m" id="rubric" v-model="rubric_id" @change="setRubric()">
                            <option :value="''">Выберите рубрику</option>
                            <option :value="0">Выберите рубрику</option>
                            <option :value="item.id" v-for="item in rubric_list" v-if="item.ctype == 'blogs'" v-bind:key="item.id">{{item.name}}
                            </option>
                        </select>
                    </div>
                </div>
            </div>
        </header>
        <div v-if="list && list.length">
            <div class="uk-margin-top">
                <widget-paginator-life-component :key="'upp'" :list="page_list" :current_page="parseInt(current_page_local)"></widget-paginator-life-component>
                <div class="uk-overflow-auto">
                    <table class="uk-table uk-table-small uk-table-divider uk-table-hover uk-background-default">
                        <thead>
                        <tr>
                            <th>Тип</th>
                            <th>Заголовок</th>
                            <th>Фото</th>
                            <th>Дата</th>
                        </tr>
                        </thead>
                        <tbody>
                        <widget_item-component v-for="item in list" :item="item" :key="item.id" @deleteNews="showDelDialog"></widget_item-component>
                        </tbody>
                    </table>
                </div>

                <widget-paginator-life-component :key="'down'" :list="page_list" :current_page="parseInt(current_page_local)"></widget-paginator-life-component>
            </div>
        </div>
        <div v-else class="uk-margin-top">
            <widget-preloader-component :load="load"></widget-preloader-component>
        </div>
        <div id="delete-save" ref="delete-save" uk-modal>
            <div class="uk-modal-dialog uk-modal-body">
                <h2 class="uk-modal-title uk-text-warning">Предупреждение!</h2>
                <p> Вы действительно хотите удалить материал?</p>
                <p class="uk-text-right">
                    <button class="uk-button uk-button-default uk-modal-close" type="button">Нет</button>
                    <button class="uk-button uk-button-primary" @click="deleteItem" type="button">ДА!</button>
                </p>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['current_page', 'rubric_id'],
        data() {
            return {
                current_page_local: this.current_page,
                queue_count: 0,
                build_queue_count: 0,
                load: false,
                delete_dialog: null,
                delete_item: null,
                list: [],
                page_list: null,
                path: window.location.pathname,
                rubric_list: [],
                keyword: '',
                view: 'list',
            }
        },
        mounted() {
            this.view = localStorage.getItem('view_type') || 'list';
            this.delete_dialog = this.$refs['delete-save'];
            this.$http.get('/admin/rubrics/get/all').then(response => {
                this.rubric_list = response.data
            });
            this.getList();
        },
        methods: {            
            getSearch: function () {
                if (this.keyword.length > 2) {
                    this.current_page_local = 1;
                    this.getList();
                }
                else if (this.keyword.length == 0) {
                    this.getList();
                }
            },
            getSearchKey: function (e) {
                if (e.keyCode === 13) {
                    this.getSearch();
                }
            },
            setRubric: function () {
                this.current_page_local = 1;
                this.getList();
            },
            getList: function () {

                let path = '';
                if (this.rubric_id) {
                    path = '/admin/blogs/get/' + this.current_page_local + '/' + this.rubric_id + (this.keyword ? '/?s='+this.keyword:'');
                } else {
                    path = '/admin/blogs/get/' + this.current_page_local + (this.keyword ? '/?s='+this.keyword:'');
                }

                this.$http.get(path).then(response => {                    
                    console.log(response.data)
                    this.load = true;
                    this.list = response.data.list;
                    this.page_list = response.data.page_list
                });
            },
            showDelDialog: function (item) {
                this.delete_item = item;
                UIkit.modal(this.delete_dialog).show();
            },
            deleteItem: function () {
                this.$http.post('/admin/blogs/delete/' + this.delete_item.id, {_method: 'DELETE'}).then((response) => {
                    UIkit.modal(this.delete_dialog).hide();
                    this.getList();
                }, (error) => {
                    if (error.response.status === 405) {
                        UIkit.notification({message: 'У вас нет доступа!', status: 'danger'});
                        UIkit.modal(this.delete_dialog).hide();
                    } else {
                        UIkit.notification({message: 'При удалении произошла ошибка!', status: 'danger'});
                        UIkit.modal(this.delete_dialog).hide();

                    }
                });
            },
            Up: function (item) {
                this.setSort(item.id, 'up');
            },
            Down: function (item) {
                this.setSort(item.id, 'down')
            },
            setSort: function (id) {
                this.$http.post('/admin/blogs/set/sort', {id: id}).then(response => {
                    this.list = response.data;
                });
            }
        },
    }
</script>

<style>

    .test-pr {
        background-color: #26a69a;
        position: relative;
        height: 10px;
        overflow: hidden;
    }

    .test-pr::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        will-change: left, right;
        background: #acece6;
        width: 25%;
        /*-webkit-animation: indeterminate 2.1s cubic-bezier(0.65, 0.815, 0.735, 0.395) infinite;*/
        /*animation: indeterminate 2.1s cubic-bezier(0.65, 0.815, 0.735, 0.395) infinite;*/
        animation: loader 2s infinite cubic-bezier(0, 0, 1, 1.01);
    }
</style>