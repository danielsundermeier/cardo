<template>

    <table-base :is-loading="isLoading" :paginate="paginate" :items-length="items.length" :has-filter="hasFilter()" @creating="create" @paginating="filter.page = $event" @searching="searching($event)">

        <template v-slot:form>
            <div class="form-group mb-0 mr-1">
                <input-text v-model="form.name" placeholder="Name" :error="error('name')" @keydown.enter="create"></input-text>
            </div>
            <div class="form-group mb-0 mr-1">
                <select class="form-control form-control-sm" :class="'partner_id' in errors ? 'is-invalid' : ''" v-model="form.partner_id">
                    <option :value="null">Kunde wählen</option>
                    <option :value="partner.id" v-for="partner in partners">{{ partner.name }}</option>
                </select>
                <div class="invalid-feedback" v-text="'partner_id' in errors ? errors.partner_id[0] : ''"></div>
            </div>
        </template>

        <template v-slot:filter>

        </template>

        <template v-slot:thead>
            <tr>
                <th class="">Name</th>
                <th class="">Gültig ab</th>
                <th class="">Aktiv</th>
                <th class="text-right d-none d-sm-table-cell w-action">Aktion</th>
            </tr>
        </template>

        <template v-slot:tbody>
            <row :item="item" :key="item.id" v-for="(item, index) in items" @deleted="deleted(index)" @updated="updated(index, $event)"></row>
        </template>

    </table-base>

</template>

<script>
    import row from './row.vue';
    import tableBase from '../../tables/base.vue';
    import inputText from '../../form/input/text.vue';

    import { baseMixin } from '../../../mixins/tables/base.js';
    import { paginatedMixin } from '../../../mixins/tables/paginated.js';

    export default {

        components: {
            inputText,
            row,
            tableBase,
        },

        mixins: [
            baseMixin,
            paginatedMixin,
        ],

        props: {
            partners: {
                required: true,
                type: Array,
            },
        },

        data () {
            return {
                filter: {
                    //
                },
                form: {
                    name: '',
                    partner_id: null,
                },
            };
        },

        computed: {

        },

        methods: {
            //
        },
    };
</script>