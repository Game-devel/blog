<template>
    <div>
        <header class="uk-content-header uk-background-default">
            <div class="title"><h2>Блоги</h2></div>
            <p class="uk-custom-grid@s">
                <a :href="'/admin/blogs/index'" class="uk-button uk-button-small uk-button-default">
                    <span uk-icon="reply"></span>К списку "блогов"
                </a>
                <a :href="'/admin/blogs/edit/'+id" class="uk-button uk-button-small uk-button-primary"><span
                        uk-icon="file-edit"></span>Редактировать</a>
                <button
                        class="uk-button uk-button-small uk-button-danger" @click="showDelDialog">
                    <span uk-icon="trash"></span>Удалить "Блог"
                </button>
            </p>
        </header>
        <div class="uk-margin-top">
            <article class="uk-textarea uk-padding uk-form-horizontal">
                <div class="uk-card uk-card-default" :class="classObject">
                    <h3 class="uk-text-center" v-html="getStatus()"></h3>
                </div>
                <div class="uk-form-horizontal uk-margin-large uk-container-small">
                    <div class="uk-margin">
                        <label class="uk-form-label">Уникальный номер:</label>
                        <div class="uk-form-controls">
	                        <p class="uk-width-1-1" v-html="list.id || 'Не назначено'"></p>
                        </div>
                    </div>
                    <div class="uk-margin">
                        <label class="uk-form-label">Новость создан:</label>
                        <div class="uk-form-controls">
	                        <p class="uk-width-1-1" v-html="list.created_at || 'Не назначено'"></p>
                        </div>
                    </div>
                    <div class="uk-margin">
                        <label class="uk-form-label">Начало редактирования:</label>
                        <div class="uk-form-controls">
	                        <p class="uk-width-1-1" v-html="list.updated_at || 'Не назначено'"></p>
                        </div>
                    </div>
                    <div class="uk-margin">
                        <label class="uk-form-label">Заголовок:</label>
                        <div class="uk-form-controls">
                            <strong class="uk-width-1-1" v-html="list.name || 'Не назначено'"></strong>
                        </div>
                    </div>
                    <div class="uk-margin">
                        <label class="uk-form-label">Текст:</label>
                        <div class="uk-form-controls" v-html="list.text">
                        </div>
                    </div>
                    <br>
                    <hr>
                    <div class="uk-margin">
                        <label class="uk-form-label">Главное фото:</label>
                        <div class="uk-form-controls">
                            <img :src=" '/uploads/blogs/blog_'+id + '/' + list.image" alt="">
                        </div>
                    </div>
                </div>
            </article>
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
        props: ['id'],
        data() {
            return {
                list: {
                    status: ''
                },
                status: '',
            }
        },
        mounted() {
            this.delete_dialog = this.$refs['delete-save'];
            this.getItem();
        },
        methods: {
            getStatus: function () {
                let status = {
                    '0': 'Черновик',
                    '2': 'Не опубликована',
                    '1': 'Опубликована',
                    '3': 'Запланирована' + "  <strong style='color:#fff;'>" + (this.list.planned ? this.list.planned.planned_to : '') + "</strong>",
                };
                return status[this.list.status];
            },
            getItem: function () {
                this.$http.get('/admin/blogs/single/get/' + this.id).then(response => {
                    this.list = response.data;
                    this.status = this.list.status;
                    this.edit_by = this.list.user_id;
                    this.publish_by = this.list.publish_by;
                });
            },
            showDelDialog: function () {
                UIkit.modal(this.delete_dialog).show();
            },
            deleteItem: function () {
                this.$http.post('/admin/blogs/delete/' + this.list.id, {_method: 'DELETE'}).then(response => {
                    UIkit.modal(this.delete_dialog).hide();
                    setTimeout(function () {
                        location.href = '/admin/blogs/index';
                    }, 500);
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
        },
        computed: {
            classObject: function () {
                return {
                    'uk-status-new': this.list.status === 0,
                    'uk-status-published': this.list.status === 1,
                    'uk-status-hidden': this.list.status === 2,
                    'uk-status-planned': this.list.status === 3
                }
            }
        }
    }
</script>
<style>
	iframe{
		width: 100%;
	}
</style>
