<template>
    <div>
        <header class="uk-content-header uk-background-default">
            <div class="title"><h2>Блоги</h2></div>
            <p class="uk-custom-grid@s">
                <a :href="'/admin/blogs/single/'+id" class="uk-button uk-button-small uk-button-default">
                    <span uk-icon="reply"></span>К просмотру "блогов"
                </a>
                <a :href="list.link" target="_blank" class="uk-button uk-button-small uk-button-secondary">
                    <span uk-icon="world"></span>Просмотреть на сайте
                </a>
            </p>
        </header>
        <div class="uk-margin-top">
            <article class="uk-textarea uk-padding uk-form-horizontal">
                <div class="uk-margin-large uk-container-small">
                    <div class="uk-margin">
                        <label class="uk-form-label" for="date">Дата:*</label>
                        <div class="uk-form-controls uk-overflow-auto uk-width-1-3@m">
                            <input v-model="list.created_at"  class="uk-input" id="date" type="datetime-local"
                                   placeholder="Заголовок" autocomplete="off">
                        </div>
                    </div>
                    <div class="uk-margin">
                        <label class="uk-form-label" for="name">Заголовок:*</label>
                        <div class="uk-form-controls">
                            <input v-model="list.name" class="uk-input" id="name" type="text"
                                   placeholder="Заголовок" autocomplete="off">
                        </div>
                    </div>
                    <div class="uk-margin">
                        <label class="uk-form-label" for="text">Текст:</label>
                        <div class="uk-form-controls ">
                            <html-editor-component :id="'text'" :is_card="list.is_card"
                                                   v-model="list.text"></html-editor-component>
                        </div>
                    </div>
                    <div class="uk-margin">
                        <label class="uk-form-label">Картинка:</label>
                        <div class="uk-form-controls">
                            <ul class="uk-list" uk-margin>
                                <li><img v-if="list.image && !file_name" :src="'/uploads/blogs/blogs_'+list.id+'/'+list.image" :width="150" alt=""></li>
                                <li><input v-model="file_name" class="uk-input" id="photo_2" type="text" placeholder="Фото" autocomplete="off"></li>
                                <li><div class="js-upload uk-placeholder uk-text-center uk-position-relative">
                                        <input :style="{opacity:0, zIndex: 1}" class="uk-height-1-1 uk-position-top-left uk-width-1-1" type="file" accept=".jpg,.png,.jpeg,.gif,.mp4" @change="onUpload(['image'],$event)">
                                        <span uk-icon="icon: cloud-upload"></span>
                                        <span class="uk-text-middle">Перетащите файлы сюда или</span>
                                        <div uk-form-custom>
                                            <span class="uk-link">загрузите вручную</span>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <hr>
                    <div v-if="list.status != 1" class="uk-margin">
                        <label class="uk-form-label">Рубрики*:</label>
                        <div class="uk-form-controls uk-height-large uk-overflow-auto uk-width-1-3@m">
                            <blogs_rubric_list-component :data="list"
                                                        @update="setRubricId"></blogs_rubric_list-component>
                        </div>
                    </div>
                    <hr>
                    <div class="uk-margin">
                        <label class="uk-form-label">Статус:</label>
                        <div class="uk-form-controls uk-overflow-auto uk-width-1-3@m">
                            <select v-model="list.status" v-on:change="CheckPlanned()" class="uk-select">
                                <option value="0">Черновик</option>
                                <option value="1">Опубликован</option>
                                <option value="2">Скрыт</option>
                                <!-- <option value="3">Запланирован</option> -->
                            </select>
                        </div>
                    </div>
                    <div class="uk-margin planned-calendar" v-bind:style="[(list.planned_to && list.status != 1) ? {} : {'display': 'none'}]">
                        <label class="uk-form-label" for="planned_to">Запланирован на:</label>
                        <div class="uk-form-controls uk-overflow-auto uk-width-1-3@m">
                            <input v-model="list.planned_to" class="uk-input" id="planned_to" type="datetime-local"
                                   placeholder="Заголовок" autocomplete="off">
                        </div>
                    </div>

                    <hr>
                    <div class="uk-width-1-1 uk-text-center uk-margin-top uk-button-group uk-custom-grid@s">
                        <button @click="checkItem(false)" :disabled="disabled_button ? true : false" class="uk-button uk-button-success" type="button">Сохранить
                        </button>
                        <button @click="checkItem(true)" :disabled="disabled_button ? true : false" class="uk-button uk-button-success" type="button">Сохранить и
                            выйти
                        </button>
                        <a :href="'/admin/blogs/single/'+this.id" class="uk-button uk-button-danger"
                           type="button">Отмена</a>
                        <span v-if="disabled_button" class="uk-margin-auto-vertical uk-margin-left" uk-spinner="ratio: 0.7"></span>
                    </div>
                </div>
            </article>
        </div>
        <div id="modal-save" ref="modal-save" uk-modal>
            <div class="uk-modal-dialog uk-modal-body">
                <h2 class="uk-modal-title uk-text-warning">Предупреждение!</h2>
                <p> Вы действительно хотите сохранить не готовый материал?</p>
                <p class="uk-text-right">
                    <button class="uk-button uk-button-default uk-modal-close" type="button">Отмена</button>
                    <button class="uk-button uk-button-primary" @click="saveItem()" type="button">Сохранить</button>
                </p>
            </div>
        </div>
        <div id="modal-save-overlay" ref="modal-save-overlay" uk-modal>
            <div class="uk-modal-dialog uk-modal-body">
                <h2 class="uk-modal-title uk-text-warning">Подождите!</h2>
                <p>Материал сохраняется</p>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['id'],
        data() {
            return {
                formdata: new FormData(),
                send_data: false,
                image_list: [],
                show_tilda: false,
                save_dialog: null,
                save_dialog_overlay: null,
                tag_name: '',
                tag_list: [],
                file_name: '',
                user_list: [],
                list: {
                    text: '',
                    show_image: true,
                },
                editorSettings: {
                    modules: {
                        toolbar: true,
                    }
                },
                planned_date: '',
                planned_time: '00:00',
                disabled_button: false,
            }
        },
        mounted() {
            this.save_dialog_overlay = this.$refs['modal-save-overlay'];
            this.save_dialog = this.$refs['modal-save'];

            this.$http.get('/admin/blogs/single/get/' + this.id).then(response => {
                this.list = response.data;
                this.list.created_at = this.$parent.formatDate(this.list.created_at)
                if (this.list.planned) {
                    this.list.planned_to = this.$parent.formatDate(this.list.planned.planned_to)
                }
                if (!this.list.tags) {
                    this.list.tags = []
                }
            });

            window.addEventListener('keydown', (event) => {
                if (event.ctrlKey && event.shiftKey) {
                    switch (event.keyCode) {
                        case 83:
                            event.preventDefault();
                            this.exit = true;
                            this.saveItem();
                            break;
                    }
                } else if (event.ctrlKey) {
                    switch (event.keyCode) {
                        case 83:
                            event.preventDefault();
                            this.saveItem();
                            break;
                    }
                }
            });
        },
        created() {
        },
        methods: {
            getImage: function (img) {
                this.image_list = img;
            },
            setRubricId: function (e) {
                this.list.rubric_id = e.id;
            },
            checkItem: function (exit) {
                this.exit = exit;
                this.saveItem();
            },
            saveItem: function () {
                if (this.disabled_button == true) {
                    return false;
                }
                this.disabled_button = true;
                if (!this.send_data) {
                    this.send_data = true;
                    this.formdata.append('list', JSON.stringify(this.list));
                    UIkit.modal(this.save_dialog).hide();
                    UIkit.modal(this.save_dialog_overlay).show();
                    this.$http.post('/admin/blogs/save/' + this.id, this.formdata).then(response => {
                        this.disabled_button = false;
                        UIkit.notification({message: 'Новость успешно сохранена!', status: 'success'});
                        setTimeout(() => {
                            UIkit.modal(this.save_dialog_overlay).hide();
                        }, 300);
                        this.send_data = false;
                        if (this.exit) {
                            setTimeout(() => {
                                location.href = '/admin/blogs/single/' + this.id;
                            }, 500);
                        }
                    }, reject => {
                        this.disabled_button = false;
                        UIkit.notification({message: 'При сохранении новости призошла ошибка!', status: 'danger'});
                        this.send_data = false;
                        setTimeout(() => {
                            UIkit.modal(this.save_dialog_overlay).hide();
                        }, 300);
                    });
                }
                else {
                    this.disabled_button = false;
                }
            },
            getParameterByName: function (name, url) {
                if (!url) url = window.location.href;
                name = name.replace(/[\[\]]/g, '\\$&');
                let regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
                    results = regex.exec(url);
                if (!results) return null;
                if (!results[2]) return '';
                return decodeURIComponent(results[2].replace(/\+/g, ' '));
            },
            Change: function (e) {
            },
            ImageUpload: function (file, Editor, cursorLocation, resetUploader) {
                let formdata = new FormData();
                formdata.append('file', file);

                this.$http.post('/admin/upload/image', formdata).then((result) => {
                    let url = result.data.url;
                    Editor.insertEmbed(cursorLocation, 'image', url);
                    resetUploader();
                }).catch((err) => {
                })
            },
            onUpload: function (fields, e,) {
                let files = e.target.files;
                for (let i = 0; i < files.length; i++) {
					if (this.$parent.checkAcceptFile(files[i])) {
                        this.formdata.append('image', files[i]);
                        this.file_name = files[i].name;
					} else {
						UIkit.notification({
							message: `Нельзя загрузить файл <strong>"` + files[i].name + `"</strong> не верный тип файла ` + files[i].type + ` !`,
							status: 'danger'
						});
					}
                }
            },
            CheckPlanned: function() {
                if (this.list.status == 3) {
                    document.querySelector('.planned-calendar').style = 'display: block'
                }
                else {
                    document.querySelector('.planned-calendar').style = 'display: none'
                }
            },
        },
    }
</script>
