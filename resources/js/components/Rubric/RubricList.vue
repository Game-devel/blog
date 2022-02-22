<template>
	<div>
		<header class="uk-content-header uk-background-default">
			<div class="title"><h2>Рубрики</h2>
				<div class="uk-float-right">
					<a href="/admin/rubrics/create" class="uk-button uk-button-primary uk-border-rounded">Создать Рубрику</a>
				</div>
			</div>
            <div class=" uk-child-width-1-2@m uk-grid">
				<div class="uk-margin uk-margin-small-top">
					<label class="uk-form-label" for="header">Найти:</label>
					<div class="uk-form-controls">
						<div class="uk-inline uk-width-1-1">
							<button class="uk-button-primary uk-form-icon uk-form-icon-flip uk-margin-remove"
									uk-icon="icon: search"></button>
							<input v-model="keyword" class="uk-input" id="header" type="text" placeholder="Название" @keyup="getSearch" autocomplete="off">
						</div>
					</div>
				</div>
			</div>
		</header>
		<div class="uk-margin-top">
			<div v-if="list && list.length">
			<widget-paginator-life-component :key="'upp'" :list="page_list" :current_page="current_page" :path="'/admin/rubrics/'"></widget-paginator-life-component>
			<ul class="uk-child-width-1-2@m" uk-grid="margin:uk-grid-margin-small">
				<li v-for="item in list" :key="item.id">
					<rubric_list_item-component :item="item" :key="item.id" @update="Update" @onDelete="Delete"></rubric_list_item-component>
				</li>
			</ul>
			<widget-paginator-life-component :key="'down'" :list="page_list" :current_page="current_page" :path="'/admin/rubrics/'"></widget-paginator-life-component>
			</div>
			<div v-else class="uk-margin-top">
				<widget-preloader-component :load="load"></widget-preloader-component>
			</div>
		</div>
	</div>
</template>

<script>
    export default {
        props: ['current_page'],
        data() {
            return {
                list: [],
                page_list: null,
                path: window.location.pathname,
                load:false,
				rubric_name: '',
				keyword: '',
            }
        },
        mounted() {
            this.getData();
        },
        methods: {
            getData: function () {
                this.$http.get('/admin/rubrics/get/' + this.current_page).then(response => {
                    this.list = response.data.list;
                    this.page_list = response.data.page_list;
                    this.load = true;
                });
            },
			getSearch: function () {
                if (this.keyword.length > 2) {
                    let path = '/admin/rubrics/get/search/?s=' + this.keyword;
                    this.$http.get(path).then(response => {
						this.list = response.data.list;
						this.page_list = 0;
						this.load = true;
                    });
                }
                else if (this.keyword.length == 0) {
                    this.$http.get('/admin/rubrics/get/' + this.current_page).then(response => {
						this.list = response.data.list;
						this.page_list = response.data.page_list;
						this.load = true;
					});
                }
            },
            Update:function (data) {
                location.href = `/admin/rubrics/edit/${data.id}`;
            },
            Delete:function(data) {
			    this.$http.post(`/admin/rubrics/delete/${data.id}`, {_method: 'DELETE'}).then(response => {
                    this.getData();
                });

            }
		},
    }
</script>
