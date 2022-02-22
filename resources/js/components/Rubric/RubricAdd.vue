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
        data() {
            return {
                save_dialog: null,
                list: {
                },
                rules: {
                    name: 'required',
                },
                formdata: new FormData(),
            }
        },
        mounted() {
        },
        methods: {
            rubricSave: function (exit) {
                if (this.$validator.run(this.list, this.rules)) {
                    this.formdata.append('list', JSON.stringify(this.list));
                    this.$http.post('/admin/rubrics/save', this.formdata).then(response => {
                        UIkit.notification({message: 'Рубрика успешно создан!', status: 'success'});
                        setTimeout(() => {
                            location.href = '/admin/rubrics/';
                        }, 500);
                    });
                }
            }
        },
    }
</script>
