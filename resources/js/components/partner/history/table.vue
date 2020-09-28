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
                        <th width="100%">Datum</th>
                        <th class="text-right" width="100">Aktion</th>
                    </tr>
                </thead>
                <tbody>
                    <row :item="item" :uri="uri" :key="item.id" v-for="(item, index) in items" @deleted="deleted(index)" @updated="updated(index, item)"></row>
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
            model: {
                type: Object,
                required: true,
            }
        },

        computed: {
            page() {
                return this.filter.page;
            },
        },

        data () {
            return {
                errors: {},
                filter: {
                    page: 1,
                    searchtext: '',
                },
                items: [],
                isLoading: true,
                paginate: {
                    nextPageUrl: null,
                    prevPageUrl: null,
                    lastPage: 0,
                },
                uri: this.model.path + '/history',
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
                        location.href = response.data.path;
                })
                    .catch(function (error) {
                        component.errors = error.response.data.errors;
                });
            },
            fetch() {
                var component = this;
                component.isLoading = true;
                axios.get(component.uri, {
                    params: component.filter
                })
                    .then(function (response) {
                        component.items = response.data.data;
                        component.filter.page = response.data.current_page;
                        component.paginate.nextPageUrl = response.data.next_page_url;
                        component.paginate.prevPageUrl = response.data.prev_page_url;
                        component.paginate.lastPage = response.data.last_page;
                        component.isLoading = false;
                    })
                    .catch(function (error) {
                        Vue.error('Datensätze konnten nicht geladen werden!');
                        console.log(error);
                    });
            },
            deleted(index) {
                this.items.splice(index, 1);
                Vue.success('Datensatz gelöscht.');
            },
            updated(index, item) {
                Vue.set(this.items, index, item);
                Vue.success('Datensatz gespeichert.');
            },
        },
    };
</script>