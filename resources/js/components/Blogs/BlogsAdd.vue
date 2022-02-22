<template>
    <div>
        <header class="uk-content-header uk-background-default">
            <div class="title"><h2>Блоги</h2></div>
        </header>
        <div class="uk-margin-top">
            <article class="uk-textarea uk-padding uk-form-horizontal">
                <div class="uk-margin-large uk-container-small">
                    <div class="uk-margin">
                        <label class="uk-form-label" for="header">Заголовок:*</label>
                        <div class="uk-form-controls">
                            <input v-model="list.name" class="uk-input" id="header" type="text"
                                   placeholder="Заголовок" autocomplete="off">
                        </div>
                    </div>
                    <div class="uk-margin">
                        <label class="uk-form-label" for="text">Текст:</label>
                        <div class="uk-form-controls ">
                            <html-editor-component
                                                   v-model="list.text"></html-editor-component>
                        </div>
                    </div>
                    <div class="uk-margin">
                        <label class="uk-form-label">Картинка:*</label>
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
                    <div class="uk-margin">
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

                    <div class="uk-margin planned-calendar" style="display: none;">
                        <label class="uk-form-label" for="planned_to">Запланирован на:</label>
                        <div class="uk-form-controls uk-overflow-auto uk-width-1-3@m">
                            <input v-model="list.planned_to" class="uk-input" id="planned_to" type="datetime-local"
                                   placeholder="Заголовок" autocomplete="off">
                        </div>
                    </div>                
                    

                    <div class="uk-width-1-1 uk-text-center uk-margin-top uk-button-group uk-custom-grid@s">
                        <button @click="addCheck(false)" :disabled="disabled_button ? true : false" class="uk-button uk-button-success" type="button">Создать
                        </button>
                        <a :href="'/admin/blogs/index/'" :disabled="disabled_button ? true : false" class="uk-button uk-button-danger" type="button">Отмена</a>
                        <span v-if="disabled_button" class="uk-margin-auto-vertical" uk-spinner="ratio: 0.7"></span>
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
                    <button class="uk-button uk-button-primary" @click="newsSave()" type="button">Сохранить</button>
                </p>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['id', 'card'],
        data() {
            return {           
                formdata: new FormData(),
                image_list: [],
                save_dialog: null,
                send: false,
                file_name: '',
                list: {
                    status: 0,
                    text: '',
                },
                rules: {
                    name: 'required',
                    rubric_id: 'required',
                    image: 'required'
                },
                disabled_button: false
            }
        },
        mounted() {
            this.save_dialog = this.$refs['modal-save'];
        },
        methods: {
            getImage: function (img) {
                this.image_list = img;
            },
            setRubricId: function (e) {
                this.list.rubric_id = e.id;
            },
            addCheck: function () {
                this.addBlogs();
            },
            addBlogs: function () {
                this.disabled_button = true;
                if (this.$validator.run(this.list, this.rules) && !this.send) {
                    this.send = true;
                    this.formdata.append('list', JSON.stringify(this.list));                    
                    this.$http.post('/admin/blogs/save', this.formdata, 
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                        }
                    }
                    ).then(response => {
                        this.disabled_button = false;
                        UIkit.notification({message: 'Блог успешно создан!', status: 'success'});
                        UIkit.modal(this.save_dialog).hide();
                        let list = response.data;
                        if (list.id) {
                            setTimeout(() => {
                                this.send = true;
                                location.href = '/admin/blogs/single/' + list.id;
                            }, 500);

                        }
                    })
                    .catch(error => {
                        if (error.response) {
                            // && error.response.status === 400
                            // console.log(error.response.status + '-' + error.response.data.error);
                            this.disabled_button = false;
                            UIkit.notification({message: error.response.status + '-' + error.response.data.error, status: 'danger'});
                        }
                    });
                }
                else {
                    this.disabled_button = false;
                }
            },
            onUpload: function (fields, e,) {
                let files = e.target.files;                

                for (let i = 0; i < files.length; i++) {                    
					if (this.$parent.checkAcceptFile(files[i])) {
                        this.list.image = true;
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
            }
        },
    }
</script>