<template>
    <div class="list-group-item list-group-item-action">
        <div class="d-flex w-100 justify-content-between">
          <h5 class="mb-1">{{ item.user.name }}</h5>
          <small>{{ item.created_at_formatted }}</small>
        </div>
        <a :href="item.commentable.path" v-if="'commentable' in item">{{ item.commentable.name }}</a>
        <p class="mb-1 whitespace-pre" v-html="item.text"></p>
    </div>
</template>

<script>
    export default {

        props: {
            item: {
                type: Object,
                required: true,
            }
        },

        data () {
            return {

            };
        },

        methods: {

        }

    };
</script>