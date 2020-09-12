<template>
    <tr>
        <td class="align-middle">
            <label class="form-checkbox"></label>
            <input :checked="selected" type="checkbox" :value="id"  @change="$emit('input', id)" number>
        </td>
        <td class="align-middle pointer" @click="link">{{ item.date }}</td>
        <td class="align-middle pointer" @click="link">{{ item.name }}</td>
        <td class="align-middle"><a :href="'/kontakte/' + item.partner.id">{{ item.partner.name }}</a></td>
        <td class="align-middle text-right pointer" @click="link">{{ (item.net / 100).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2, }) }}</td>
        <td class="align-middle text-right pointer" @click="link">{{ (item.gross / 100).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2, }) }}</td>
        <td class="align-middle text-right">
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-secondary" title="Bearbeiten" @click="link"><i class="fas fa-edit"></i></button>
                <button type="button" class="btn btn-secondary" title="Löschen" @click="destroy"><i class="fas fa-trash"></i></button>
            </div>
        </td>
    </tr>
</template>

<script>
    export default {

        props: [ 'item', 'uri', 'selected' ],

        data () {
            return {
                id: this.item.id,
                path: '/' + this.uri + '/' + this.item.id
            };
        },

        methods: {
            destroy() {
                var component = this;
                axios.delete(component.path)
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
                location.href = this.path;
            },
        },
    };
</script>