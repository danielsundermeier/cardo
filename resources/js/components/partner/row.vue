<template>
    <tr>
        <td class="align-middle pointer" @click="show">
            {{ item.number }}
        </td>
        <td class="align-middle pointer" @click="show">
            {{ item.name }}
            <span class="text-muted" v-if="item.is_client && ! item.is_active">inaktiv</span>
        </td>
        <td class="align-middle pointer"><span v-html="item.courses_string" v-if="uri == 'client'"></span></td>
        <td class="align-middle text-right">
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-secondary" title="Bearbeiten" @click="edit"><i class="fas fa-edit"></i></button>
                <button type="button" class="btn btn-secondary" title="Löschen" @click="destroy" v-if="item.is_deletable"><i class="fas fa-trash"></i></button>
            </div>
        </td>
    </tr>
</template>

<script>
    export default {

        components: {

        },

        props: {
            item: {
                type: Object,
                required: true,
            },
            uri: {
                type: String,
                required: true,
            },

        },

        data () {
            return {
                id: this.item.id,
            };
        },

        methods: {
            destroy() {
                var component = this;
                axios.delete(component.item.path)
                    .then(function (response) {
                        if (response.data.deleted) {
                            component.$emit("deleted", component.id);
                            Vue.success('Datensatz gelöscht.');
                        }
                        else {
                            Vue.error('Datensatz konnte nicht gelöscht werden!');
                        }
                    });
            },
            edit() {
                location.href = this.item.edit_path;
            },
            show() {
                location.href = this.item.path;
            },
        },
    };
</script>