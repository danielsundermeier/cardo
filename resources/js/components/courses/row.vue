<template>
    <tr>
        <td class="align-middle pointer" @click="link">{{ item.name }}</td>
        <td class="align-middle pointer" @click="link">{{ item.instructor.name }}</td>
        <td class="align-middle text-right">
            <div class="btn-group btn-group-sm" role="group">
                <a :href="item.edit_path" type="button" class="btn btn-secondary" title="Bearbeiten" @click="link"><i class="fas fa-edit"></i></a>
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
            link () {
                location.href = this.item.path;
            },
        },
    };
</script>