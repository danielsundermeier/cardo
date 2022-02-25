<template>
    <tr>
        <td class="align-middle"><a :href="item.partner.path"layouts.guest>{{ item.partner.name }}</a></td>
        <template v-if="item.has_subscription">
            <td class="align-middle text-right d-none d-sm-table-cell">Abo</td>
            <td class="align-middle text-right">
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-secondary d-none d-sm-table-cell" title="Deaktivieren" @click="deactivate" v-if="item.is_active"><i class="fas fa-fw fa-check"></i></button>
                    <button type="button" class="btn btn-secondary d-none d-sm-table-cell" title="Aktivieren" @click="activate" v-if="! item.is_active"><i class="fas fa-fw fa-times"></i></button>
                    <button type="button" class="btn btn-secondary" title="Löschen" @click="destroy" v-if="item.is_deletable"><i class="fas fa-fw fa-trash"></i></button>
                </div>
            </td>
        </template>
        <template v-else>
            <td class="align-middle text-right d-none d-sm-table-cell">{{ item.open_participations_count }}</td>
            <td class="align-middle text-right">
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-secondary" title="10er Karte kaufen" @click="create">+10</button>
                    <button type="button" class="btn btn-secondary d-none d-sm-table-cell" title="Deaktivieren" @click="deactivate" v-if="item.is_active"><i class="fas fa-fw fa-check"></i></button>
                    <button type="button" class="btn btn-secondary d-none d-sm-table-cell" title="Aktivieren" @click="activate" v-if="! item.is_active"><i class="fas fa-fw fa-times"></i></button>
                    <button type="button" class="btn btn-secondary" title="Löschen" @click="destroy" v-if="item.is_deletable"><i class="fas fa-fw fa-trash"></i></button>
                </div>
            </td>
        </template>
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
            activate() {
                var component = this;
                axios.put(component.uri + '/' + this.id + '/activate', {})
                    .then( function (response) {
                        component.errors = {};
                        component.$emit('updated', response.data);
                    })
                    .catch(function (error) {
                        component.errors = error.response.data.errors;
                });
            },
            deactivate() {
                var component = this;
                axios.delete(component.uri + '/' + this.id + '/activate', {})
                    .then( function (response) {
                        component.errors = {};
                        component.$emit('updated', response.data);
                    })
                    .catch(function (error) {
                        component.errors = error.response.data.errors;
                });
            },
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