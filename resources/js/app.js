/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

// window.Vue = require('vue');
import Vue from 'vue'
import Editor from '@tinymce/tinymce-vue';
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

Vue.component('blogs_add-component', require('./components/Blogs/BlogsAdd.vue').default);
Vue.component('blogs_list-component', require('./components/Blogs/BlogsList.vue').default);
Vue.component('blogs_list_item-component', require('./components/Blogs/BlogsListItem.vue').default);
Vue.component('blogs_single-component', require('./components/Blogs/BlogsSingle.vue').default);
Vue.component('blogs_edit-component', require('./components/Blogs/BlogsEdit.vue').default);
Vue.component('blogs_rubric_list-component', require('./components/Blogs/BlogsRubricList.vue').default);

Vue.component('rubric_edit-component', require('./components/Rubric/RubricEdit.vue').default);
Vue.component('rubric_add-component', require('./components/Rubric/RubricAdd.vue').default);
Vue.component('rubric_list-component', require('./components/Rubric/RubricList.vue').default);
Vue.component('rubric_list_item-component', require('./components/Rubric/RubricListItem.vue').default);

Vue.component('html-editor-component', require('./components/Widget/HtmlEditor.vue').default);
Vue.component('rubrics_item-component', require('./components/Widget/RubricsItem.vue').default);
Vue.component('widget-preloader-component', require('./components/Widget/Preloader.vue').default);
Vue.component('widget-paginator-life-component', require('./components/Widget/PaginatorLife.vue').default);
Vue.component('widget_item-component', require('./components/Widget/IndexPageItem').default);

Vue.prototype.$http = axios;
Vue.prototype.$Editor = Vue.component('editor', Editor);

Vue.prototype.$ru = {
    authors: "Авторы",
    name: "Имя",
    header: "Заголовок",
    title: "Заголовок",
    rubric_id: "Рубрики",
    text: "Текст",
    fio: 'ФИО',
    email: 'Email',
    date: 'Дата',
    mail: 'Email',
    keyword: 'Ключевое слово',
    image: 'Картинка',
    last_update: 'Дата',
    data: 'Дата',
    surname: 'Фамилия',
    birthday: 'Дата рождения',
    password: 'Пароль',
    keywords: 'Ключевое слово',
    status: 'Статус',
};

Vue.prototype.$validator = {
    run: function (fields, rules) {
        let result = true;
        for (let field in rules) {
            let rule = rules[field];
            switch (rule) {
                case 'required':
                    switch (typeof fields[field]) {
                        case 'undefined':
                            result = false;
                            UIkit.notification({
                                message: 'Поле <strong class="' + field + '" >' + Vue.prototype.$ru[field] + '</strong> не может быть пустым !',
                                status: 'danger'
                            });
                            break;
                        case 'object':
                            if (!fields[field].length) {
                                result = false;
                                UIkit.notification({
                                    message: 'Поле <strong>' + Vue.prototype.$ru[field] + '</strong> не может быть пустым !',
                                    status: 'danger'
                                });
                            }
                            break;
                        case 'string':
                            if (!fields[field].length) {
                                result = false;
                                UIkit.notification({
                                    message: 'Поле <strong>' + Vue.prototype.$ru[field] + '</strong> не может быть пустым !',
                                    status: 'danger'
                                });
                            }
                            break;
                    }
                    break;
            }
        }
        return result;
    }
};

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    data: {
        message: ''
    },
    mounted() {},
    methods: {
        checkAcceptFile: function (file) {
            let accept_file = ['image/gif', 'image/jpeg', 'video/mp4', 'image/png', 'audio/mp3', 'audio/mpeg'];
            let index = accept_file.indexOf(file.type);
            if (index >= 0) {
                return true;
            } else {
                return false;
            }

            return true;

        },

        formatDate: function(date) {
            var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear(),
            hour = d.getHours(),
            minute = d.getMinutes();

            if (month.length < 2)
                month = '0' + month;
            if (day.length < 2)
                day = '0' + day;
            if (hour < 10)
                hour = '0' + hour;
            if (minute < 10)
                minute = '0' + minute;

            return [year, month, day].join('-') + 'T' + [hour, minute].join(':');
        },
    }
});

(function () {
    let menu = document.querySelector('#trigger_menu');
    let header = document.querySelector('header');

    let show_trigger = function (e) {

        let that = menu;

        let container = document.querySelector('.uk-container-custom');

        let nav = document.querySelector('*[data-side-nav]');
        let logo_mini = document.querySelector('#logo-mini');
        let logo_big = document.querySelector('#logo-big');
        let span = that.querySelector('span');

        that.parentNode.classList.toggle('uk-active');

        if (that.parentNode.classList.contains('uk-active')) {
            span.classList.add('collapse');
            nav.classList.add('collapse');
            header.classList.add('collapse');
            logo_mini.classList.add('active');
            logo_mini.classList.remove('uk-hidden');
            logo_big.classList.remove('active');
            logo_big.classList.add('uk-hidden');
            container.classList.add('collapse');
            localStorage.setItem('menu_collapse', true);

        } else {
            span.classList.remove('collapse');
            nav.classList.remove('collapse');
            header.classList.remove('collapse');
            logo_big.classList.add('active');
            logo_big.classList.remove('uk-hidden');
            logo_mini.classList.remove('active');
            logo_mini.classList.add('uk-hidden');
            container.classList.remove('collapse');
            localStorage.setItem('menu_collapse', false);
        }
    };

    if (JSON.parse(localStorage.getItem('menu_collapse'))) {
        let container = document.querySelector('.uk-container-custom');
        let nav = document.querySelector('*[data-side-nav]');
        let logo_mini = document.querySelector('#logo-mini');
        let logo_big = document.querySelector('#logo-big');
        let span = document.querySelector('#trigger_menu span');

        span.classList.add('collapse');
        menu.parentNode.classList.toggle('uk-active');
        nav.classList.add('collapse');
        header.classList.add('collapse');
        logo_mini.classList.add('active');
        logo_mini.classList.remove('uk-hidden');
        logo_big.classList.remove('active');
        logo_big.classList.add('uk-hidden');
        container.classList.add('collapse');
    }


    menu.addEventListener('click', show_trigger)

})();