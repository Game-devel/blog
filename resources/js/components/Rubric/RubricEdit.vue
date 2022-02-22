<template>
    <div>
        <header class="uk-content-header uk-background-default">
            <div class="title"><h2>Рубрика</h2></div>
        </header>
        <div class="uk-margin-top">
            <article class="uk-comment uk-textarea uk-padding-small">
                <header class="uk-comment-header uk-flex-middle uk-padding-small">
                    <div class=" uk-margin-large">
                        <div class="uk-margin">
                            <label class="uk-form-label" for="name">Название:*</label>
                            <div class="uk-form-controls">
                                <input v-model="list.name" class="uk-input" id="name" type="text"
                                       placeholder="Название" autocomplete="off">
                            </div>
                        </div>
                        <hr>
                        <div class="uk-width-1-1 uk-text-center uk-margin-top uk-button-group uk-custom-grid@s">
                            <button @click="rubricSave(false)" class="uk-button uk-button-success" type="button">
                                Сохранить
                            </button>
                            <button @click="rubricSave(true)" class="uk-button uk-button-success" type="button">
                                Сохранить и выйти
                            </button>
                            <a :href="'/admin/rubrics/'" class="uk-button uk-button-danger" type="button">Отмена</a>
                        </div>
                    </div>
                </header>
            </article>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['id'],
        data() {
            return {
                save_dialog: null,
                list: {
                },
                formdata: new FormData(),
            }
        },
        mounted() {
            this.$http.get('/admin/rubrics/single/get/' + this.id).then(response => {
                this.list = response.data;
            });
        },
        methods: {
            rubricSave: function (exit) {
                this.formdata.append('list', JSON.stringify(this.list));
                this.$http.post('/admin/rubrics/save/' + this.id, this.formdata).then(response => {
                    UIkit.notification({message: 'Рубрика успешно сохранена!', status: 'success'});
                    if (exit) {
                        setTimeout(() => {
                            location.href = '/admin/rubrics/';
                        }, 500);
                    }
                });
            },
        },
    }
</script>
