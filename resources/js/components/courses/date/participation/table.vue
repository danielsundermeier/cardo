<template>
    <div>
        <div class="row">
            <div class="col d-flex align-items-start mb-1 mb-sm-0">
                <div class="form-group mr-1" style="margin-bottom: 0;">
                    <select class="form-control" :class="'partner_id' in errors ? 'is-invalid' : ''" v-model="form.partner_id">
                        <option :value="null">Teilnehmer wählen</option>
                        <option :value="partner.id" v-for="partner in availablePartners">{{ partner.name }}</option>
                    </select>
                    <div class="invalid-feedback" v-text="'partner_id' in errors ? errors.partner_id[0] : ''"></div>
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <button class="btn btn-primary" title="Anlegen" @click="create"><i class="fas fa-plus-square"></i></button>
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
        <table class="table table-hover table-striped bg-white mt-3" v-else-if="items.length">
            <thead>
                <tr>
                    <th width="100%">Teilnehmer</th>
                    <th class="text-right" width="200">Offene Teilnahmen</th>
                    <th class="text-right" width="100">Aktion</th>
                </tr>
            </thead>
            <tbody>
                <row :item="item" :uri="uri" :key="item.id" v-for="(item, index) in items" @deleted="remove(index)" @updated="updated(index, $event)"></row>
            </tbody>
        </table>
        <div class="alert alert-dark mt-3" v-else>
            <center>
                Keine Daten vorhanden
                <div class="mt-3" v-if="lastDate"><button class="btn btn-primary" @click="copy">Teilnehmer vom {{ lastDate.at_formatted }} übernehmen ({{ lastDate.participations_count }})</button></div>
            </center>
        </div>
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
            },
            parent: {
                type: Object,
                required: true,
            },
            partners: {
                type: Array,
                required: true,
            },
            lastDate: {
                type: Object,
                required:false,
            },
        },

        computed: {
            availablePartners() {
                const partner_ids = this.items.reduce( function (total, participation) {
                    total.push(participation.participant.partner_id);
                    return total;
                }, []);

                function compare(a, b) {
                    if (a.name < b.name) {
                        return -1;
                    }

                    if (a.name > b.name) {
                        return 1;
                    }

                    return 0;
                }

                var component = this;

                return this.partners.filter(function (partner) {
                    return (partner_ids.indexOf(partner.id) == -1);
                }).sort(compare);
            },
        },

        data () {
            return {
                uri: '/date/' + this.model.id + '/participation',
                items: [],
                isLoading: true,
                filter: {

                },
                form: {
                    partner_id: null,
                },
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
                axios.post(component.uri, component.form)
                    .then(function (response) {
                        component.errors = {};
                        component.items.unshift(response.data);
                        component.form.partner_id = null;
                })
                    .catch(function (error) {
                        if (error.response.status == 404) {
                            Vue.error('Kunde nimmt schon teil.');
                        }
                        else {
                            component.errors = error.response.data.errors;
                        }
                });
            },
            copy() {
                var component = this;
                axios.post(component.uri + '/copy', {
                    last_date_id: component.lastDate.id,
                })
                    .then(function (response) {
                        component.errors = {};
                        component.items = response.data;
                })
                    .catch(function (error) {
                        Vue.error('Teilnehmer konnten nicht kopiert werden.');
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
            remove(index) {
                this.items.splice(index, 1);
            },
            updated(index, item) {
                Vue.set(this.items, index, item);
                Vue.success('Rechnung erstellt.');
            },
        },
    };
</script>