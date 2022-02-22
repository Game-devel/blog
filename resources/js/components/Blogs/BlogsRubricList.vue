<template>
    <div>
        <input type="text" class="uk-input" v-model="filter" placeholder="Фильтр" autocomplete="off">
        <div v-for="item in list" :key="item.id">
            <rubrics_item-component v-if="item" :item="item" :data="data"
                                        :filter_="filter"
                                        @update="Update"></rubrics_item-component>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['data', 'filter_'],
        data() {
            return {
                filter: '',
                list: null,
            }
        },
        mounted() {
            if (this.filter_) {
                this.filter = this.filter_;
            }
            this.$http.get('/admin/rubrics/get/all').then(response => {
                this.list = response.data;
            });
        },
        methods: {
            Update: function (e) {
                this.$emit('update', e);
            },
        },
        computed: {}
    }
</script>