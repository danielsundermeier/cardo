<template>
    <div>
        <div class="row">
            <div class="col d-flex align-items-start mb-1 mb-sm-0">
                <div class="form-group" style="margin-bottom: 0;">
                    <button class="btn btn-primary" title="Anlegen" @click="create"><i class="fas fa-plus-square"></i></button>
                </div>
            </div>
            <div class="col-auto d-flex">

            </div>
        </div>

        <form v-if="filter.show" id="filter" class="mt-1">
            <div  class="form-row">



            </div>
        </form>

        <div v-if="isLoading" class="mt-3 p-5">
            <center>
                <span style="font-size: 48px;">
                    <i class="fas fa-spinner fa-spin"></i><br />
                </span>
                Lade Daten..
            </center>
        </div>
        <div class="table-responsive mt-3" v-else-if="items.length">
            <table class="table table-hover table-striped bg-white">
                <thead>
                    <tr>
                        <th width="125">Datum</th>
                        <th width="100">Gewicht</th>
                        <th width="75">BMI</th>
                        <th width="150" colspan="2">Blutdruck</th>
                        <th width="75">Puls</th>
                        <th width="100">Ruhepuls</th>
                        <th class="text-right" width="100">Aktion</th>
                    </tr>
                </thead>
                <tbody>
                    <row :item="item" :uri="uri" :key="item.id" :height-in-cm="partner.height_in_cm" v-for="(item, index) in items" @deleted="deleted(index)" @updated="updated(index, item)"></row>
                </tbody>
            </table>
        </div>
        <div class="alert alert-dark mt-3" v-else><center>Keine Daten vorhanden</center></div>
    </div>
</template>

<script>
    import Row from "./row.vue";

    export default {

        components: { Row },

        props: {
            partner: {
                type: Object,
                required: true,
            }
        },

        data () {
            return {
                uri: '/partner/' + this.partner.id + '/healthdatas',
                items: [],
                isLoading: true,
                showFilter: false,
                searchtext: '',
                searchTimeout: null,
                filter: {

                },
                name: '',
                abbreviation: '',
                errors: {},
            };
        },

        mounted() {

            this.fetch();

        },

        watch: {
            page () {
                this.fetch();
            },
        },

        methods: {
            create() {
                var component = this;
                axios.post(this.uri)
                    .then(function (response) {
                        component.errors = {};
                        component.name = '';
                        component.abbreviation = '';
                        component.items.unshift(response.data);
                })
                    .catch(function (error) {
                        component.errors = error.response.data.errors;
                });
            },
            fetch() {
                var component = this;
                component.isLoading = true;
                axios.get(this.uri)
                    .then(function (response) {
                        component.items = response.data;
                        component.isLoading = false;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            search () {
                var component = this;
                if (component.searchTimeout)
                {
                    clearTimeout(component.searchTimeout);
                    component.searchTimeout = null;
                }
                component.searchTimeout = setTimeout(function () {
                    component.fetch()
                }, 300);
            },
            deleted(index) {
                this.items.splice(index, 1);
            },
            updated(index, item) {
                Vue.set(this.items, index, item);
                Vue.success('Datensatz gespeichert.');
            },
        },
    };
</script>