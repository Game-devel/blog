<template>
	<article class="uk-comment uk-textarea uk-position-relative">
		<header class="uk-comment-header uk-news-grid">
			<div class="uk-img-container uk-width-auto uk-position-relative uk-background-secondary">
				<img class="uk-comment-avatar" :src="item.thumb.url" alt="">
				<span class="uk-icon-button uk-text-success uk-position-top-left uk-margin-small-left uk-margin-small-top" v-if="item.correction_status===2" uk-icon="icon: check; ratio: 2" title="Проверено корректором"></span>
				<span v-if="item.lock" class="uk-overlay-primary uk-background-primary uk-padding-small uk-position-top-center uk-width-1-1" :title="item.lock.name+' сейчас редактирует эту запись'">
					<i uk-icon="pencil" class="uk-margin-small-right"></i>
					{{item.lock.name}}
				</span>
				<a class="uk-link-muted uk-position-bottom-right uk-background-default uk-padding-small" :href="'/admin/news/edit/gallery/'+item.id">
					<i class="uk-margin-small-right" uk-icon="image"></i>
					{{item.photo_count}}
				</a>
			</div>
			<div>
				<h4 class="uk-comment-title uk-margin-remove">
					<a class="uk-link-reset" :href="'/admin/news/single/'+item.id">{{item.header}}</a>
				</h4>
				<ul class="uk-comment-meta uk-list uk-child-padding-remove">
					<li>
						<span class="uk-link-muted">
							ID:<strong>{{item.id}}</strong>
						</span>
					</li>
					<li v-if="item.rubric">
						<span class="uk-link-muted">
							Рубрика:<strong>{{item.rubric.ru_name}}</strong>
						</span>
					</li>
					<li>
						<span class="uk-link-muted">
							<span>Создан:</span>
							<strong>{{item.created_at}}</strong>
						</span>
					</li>
					<li>
						<span class="uk-link-muted">
							<span>Отредактировано:</span>
							<strong>{{item.updated_at}}</strong>
						</span>
					</li>
				</ul>
			</div>
			<div class="uk-comment-footer uk-padding-small" :class="classObject">
				<a class="uk-button uk-button-default uk-background-muted uk-button-secondary" :href="'/admin/news/edit/'+item.id" title="Редактировать">
					<span class="uk-text-success" uk-icon="file-edit"></span>
				</a>
				<button v-if="getPermission('news','new_creating')" class="uk-button uk-button-primary" @click="copyNews" title="Копировать">
					<span uk-icon="copy"></span>
				</button>
				<button v-if="getPermission('news','set_status_hidden')" class="uk-button uk-button-danger" @click="Delete" title="Удалить">
					<span uk-icon="trash"></span>
				</button>
			</div>
		</header>
		<div class="uk-comment-body">
			<hr>
			<p class="uk-padding-small uk-padding-remove-top" v-html="item.announce"></p>
		</div>
	</article>
</template>

<script>
    export default {
        props: {
            item: {
                type: Object,
                required: true
            }
        },
        data() {
            return {}
        },
        methods: {
            copyNews: function () {
                this.$http.get('/admin/news/copy/' + this.item.id).then(response => {
                    let data = response.data;
                    if (data && data.id) {
                        UIkit.notification({message: 'Новость успешно скопирована!', status: 'success'});
                        setTimeout(() => {
                            location.href = '/admin/news/edit/' + data.id
                        }, 500);
                    }
                });
            },
            getPermission:function(section,action){
               return this.$parent.getPermission(section,action);
            },
            Delete: function () {
                this.$emit('deleteNews', this.item);
            }
        }
        ,
        computed: {
            classObject: function () {
                return {
                    'uk-status-unpublished': this.item.status === 'unpublished' || this.item.status === 0,
                    'uk-status-published': this.item.status === 'published' || this.item.status === 1,
                    'uk-status-ready': this.item.status == 'ready',
                    'uk-status-new': this.item.status === 'new' || this.item.status === 0,
                    'uk-status-editing': this.item.status == 'editing',
                    'uk-status-deleted': this.item.status == 'deleted' || this.item.status === -1,
                    'uk-status-hidden': this.item.status == 'hidden' || this.item.status === 2,
                    'uk-status-planned': this.item.status == 'planned' || this.item.status === 3									
                }
            }
        }
    }
</script>
