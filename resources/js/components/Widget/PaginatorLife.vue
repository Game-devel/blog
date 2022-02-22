<template>
	<ul class="uk-pagination uk-flex-center" v-if="list" uk-margin>
		<li><a v-if="current_page!=1" @click="changePage(current_page-1)" ><span uk-pagination-previous></span></a></li>
		<li v-if="current_page>3"><a @click="changePage(1)" >1</a></li>
		<li v-if="current_page>3" class="uk-disabled"><span>...</span></li>        
		<li v-for="item in list" :key="item" v-if="item>=current_page && item<=current_page+10" :class="{'uk-active':item==current_page}"><a @click="changePage(item)">{{item}}</a></li>        
		<li><a v-if="current_page!=list[list.length-1]" @click="changePage(current_page+1)"><span uk-pagination-next></span></a></li>
	</ul>
</template>

<script>
    export default {
        props: {
            list:null,
            current_page:{
                type:Number
			},
        },
        data() {
            return {}
        },
        methods: {    
            changePage: function(item) {
                this.$parent.current_page_local = item
                this.$parent.getList();
            }
        }
    }
</script>