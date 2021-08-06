<template>
    <div>
        <div class="row">
            <div class="col d-flex align-items-start mb-1 mb-sm-0">
                <div class="form-group mr-1" style="margin-bottom: 0;">
                    <select class="form-control form-control-sm" v-model="filter.year">
                        <option :value="year" v-for="year in years">{{ year }}</option>
                    </select>
                </div>
            </div>
        </div>

        <div v-if="isLoading" class="mt-3 p-5">
            <center>
                <span style="font-size: 48px;">
                    <i class="fas fa-spinner fa-spin"></i><br />
                </span>
                Lade Daten..
            </center>
        </div>
        <table class="table table-hover table-striped table-fixed bg-white table-sm mt-3" v-else-if="items.length">
            <thead>
                <tr>
                    <th width="100%">Teilnehmer</th>
                    <th class="text-right" width="150">Teilnahmen</th>
                </tr>
            </thead>
            <tbody>
                <row :item="item" :uri="uri" :key="item.id" v-for="(item, index) in items" @deleted="remove(index)" @updated="updated(index, $event)"></row>
            </tbody>
        </table>
        <div class="alert alert-dark mt-3" v-else><center>Keine Daten vorhanden</center></div>
    </div>
</template>

<script>
    import Row from "./row.vue";

    export default {

        components: {
            Row
        },

        props: {
            model: {
                type: Object,
                required: true,
            },
        },

        computed: {
            years() {
                var years = [];
                for (var i = 2021; i <= new Date().getFullYear(); i++) {
                    years.push(i);
                }
                return years;
            },
        },

        data () {
            return {
                uri: this.model.corrections_path,
                items: [],
                isLoading: true,
                filter: {
                    year: new Date().getFullYear(),
                },
                form: {
                    //
                },
                errors: {},
            };
        },

        mounted() {

            this.fetch();

        },

        watch: {
            //
        },

        methods: {
            fetch() {
                var component = this;
                component.isLoading = true;
                axios.get(component.model.participations_index_path, {
                    params: component.filter
                })
                    .then(function (response) {
                        component.items = response.data;
                        component.isLoading = false;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
        },
    };
</script>