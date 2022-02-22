<template>
	<div class="uk-width-1-1 uk-background-default">
		<div class="uk-background-default uk-button-group uk-width-1-1">
			<button class="uk-button uk-button-default uk-width-1-1">{{item.name}}</button>
			<div class="uk-inline">
				<button class="uk-button uk-button-default" type="button">
					<span uk-icon="icon:  triangle-down"></span></button>
				<div uk-dropdown="mode: click;">
					<ul class="uk-nav uk-dropdown-nav">
						<li><span @click="Update(item)"><span uk-icon="file-edit" class="uk-text-success"></span>Редактировать</span></li>
						<li class="uk-nav-divider"></li>
						<li><span @click="Delete(item)" style="cursor: pointer"><span uk-icon="trash" class="uk-text-danger"></span>Удалить</span></li>
					</ul>
				</div>
			</div>
		</div>
		<ul v-if="item.child && item.child.length" uk-accordion="" class="uk-padding-small uk-padding-remove-top">
			<li>
				<span class="uk-accordion-title" >Дочерние элементы</span>
				<div class="uk-accordion-content">
					<rubric_list_item-component v-for="val in item.child" :item="val" :key="val.id" @update="Update"></rubric_list_item-component>
				</div>
			</li>
		</ul>
	</div>
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
        },
        methods: {
            Update:function (data) {
                this.$emit('update', data);
            },
            Delete:function (data) {
                this.$emit('onDelete', data);
            }
        }
    }
</script>
