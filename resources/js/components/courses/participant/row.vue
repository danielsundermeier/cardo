<template>
    <tr>
        <td class="align-middle"><a :href="item.partner.path"layouts.guest>{{ item.partner.name }}</a></td>
        <td class="align-middle text-right d-none d-sm-table-cell">{{ item.open_participations_count }}</td>
        <td class="align-middle text-right">
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-secondary" title="10er Karte kaufen" @click="create">+10</button>
                <button type="button" class="btn btn-secondary" title="Löschen" @click="destroy" v-if="item.is_deletable"><i class="fas fa-fw fa-trash"></i></button>
            </div>
        </td>
    </tr>
</template>

<script>
    export default {

        props: [ 'item', 'uri' ],

        data () {
            return {
                id: this.item.id,
                errors: {},
                form: {
                    partner_id: this.item.partner_id,
                    create_invoice: true,
                },
            };
        },

        methods: {
            create() {
                var component = this;
                axios.post(component.uri, component.form)
                    .then( function (response) {
                        component.errors = {};
                        component.$emit('updated', response.data);
                    })
                    .catch(function (error) {
                        component.errors = error.response.data.errors;
                });
            },
            destroy() {
                axios.delete(this.uri + '/' + this.id);
                this.$emit('deleted', this.id);
            },
        },
    };
</script>