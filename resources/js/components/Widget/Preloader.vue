<template>
	<div v-if="load" class="uk-card uk-card-default uk-card-body uk-margin">
		<h3 v-html="getMessage()" ></h3>
	</div>
	<div v-else="" class="tn-preloader uk-margin"></div>
</template>

<script>
    export default {
        props: {
            message:null,
            load: null,
            current_page: {
                type: Boolean
            },
        },
        data() {
            return {}
        },
		methods:{
            getMessage:function () {
				return this.message ? this.message : 'По вашему запросу ничего не найдено.';
            }
		}
    }
</script>