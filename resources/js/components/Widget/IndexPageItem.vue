<template>
    <tr>
        <td :class="getClass()">
            <span>{{tr[item.type]}}</span>
            <br>
            <span>{{item.id}}</span>
            <br>
        </td>
        <td>
            <div>
                <a class="uk-h4" :href="'/admin/blogs/single/'+item.id" >{{item.title || item.header || item.name}}</a>
            </div>
        </td>
        <td>
            <a class="uk-link-muted uk-padding-small" :href="'/admin/blogs/edit/'+item.id">
                <img v-if="item.image" :src="item.image_s || '/uploads/blogs/blog_'+item.id + '/' + item.image" alt="">
            </a>
        </td>
        <td>{{item.created_at}}</td>
        <td>
            <div class="uk-inline uk-grid-2">
                <a class="uk-button uk-button-primary" :href="'/admin/blogs/edit/'+item.id"
                   title="Редактировать">
                    <span uk-icon="file-edit"></span>
                </a>
                <button class="uk-button uk-button-danger"
                        @click="Delete" title="Удалить">
                    <span uk-icon="trash"></span>
                </button>
            </div>
        </td>
    </tr>
</template>

<script>
    export default {
        props: ['item'],
        data() {
            return {
                tr: {
                    'blogs': 'Блоги',
                },
            }
        },
        mounted() {

        },
        methods: {
            Delete: function () {
                this.$emit('deleteNews', this.item);
            },
            getStatus: function () {
                return {}
            },
            getClass: function () {
                return {
                    'uk-type-article': this.item.type == 'blogs',

                    'uk-status-new': this.item.status === 0,
                    'uk-status-published': this.item.status === 1,
                    'uk-status-hidden': this.item.status === 2,
                    'uk-status-planned': this.item.status === 3
                }
            }
        }
    }
</script>
