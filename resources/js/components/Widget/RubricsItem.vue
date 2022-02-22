<template>
    <div>
        <label v-if="storiesBy()">
            <input class="uk-radio" type="radio" v-model="data.rubric_id" :value="item.id" name="rubric_id"
                   @change="Update">{{item.ru_name || item.name}}</label>
        <div class="uk-margin-left" v-if="item.child">
            <rubrics_item-component v-for="val in item.child" :filter_="filter_" :key="val.id" :item="val"
                                        @update="_Update" :data="data"></rubrics_item-component>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['item', 'data', 'filter_'],
        data() {
            return {}
        },
        methods: {
            Update: function () {
                this.$emit('update', this.item);
            },
            _Update: function (e) {
                this.$emit('update', e);
            },
            storiesBy() {
                let name = this.item.ru_name || this.item.name;
                let filter = this.filter_;
                name = name.toLowerCase();
                filter = filter.toLowerCase();
                return name.indexOf(filter) >= 0 ? true : false;
            },
        }
    }
</script>
