<template>
    <tr>
        <td class="align-middle pointer">{{ item.created_at_formatted }}</td>
        <td class="align-middle pointer">{{ item.participant.course.name }}</td>
        <td class="align-middle text-right">
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-secondary btn-sm" title="Löschen" @click="destroy" v-if="item.is_deletable"><i class="fas fa-fw fa-trash"></i></button>
            </div>
        </td>
    </tr>
</template>

<script>
    export default {

        props: {
            item: {
                type: Object,
                required: true,
            },
        },

        data () {
            return {
                //
            };
        },

        methods: {
            destroy() {
                axios.delete(this.item.path);
                this.$emit('deleted', this.item.id);
            },
        },
    };
</script>