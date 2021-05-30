<template>
    <div>
        <div class="row">
            <div class="col d-flex align-items-start mb-1 mb-sm-0">
                <div class="form-group mr-1" style="margin-bottom: 0;">
                    <select class="form-control" :class="'participant_id' in errors ? 'is-invalid' : ''" v-model="form.participant_id">
                        <option :value="null">Kurs wählen</option>
                        <option :value="participant.id" v-for="participant in model.participants">{{ participant.course.name }}</option>
                    </select>
                    <div class="invalid-feedback" v-text="'participant_id' in errors ? errors.participant_id[0] : ''"></div>
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
        <table class="table table-hover table-striped table-fixed bg-white table-sm mt-3" v-else-if="items.length">
            <thead>
                <tr>
                    <th width="150">Datum</th>
                    <th width="100%">Kurs</th>
                    <th class="text-right" width="100">Aktion</th>
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

        components: { Row },

        props: {
            model: {
                type: Object,
                required: true,
            },
            courses: {
                type: Array,
                required: true,
            }
        },

        computed: {
            availableCourses() {
                const course_ids = this.items.reduce( function (total, participant) {
                    total.push(participant.course_id);
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

                return this.courses.filter(function (partner) {
                    return (course_ids.indexOf(partner.id) == -1);
                }).sort(compare);
            },
        },

        data () {
            return {
                uri: this.model.corrections_path,
                items: [],
                isLoading: true,
                filter: {

                },
                form: {
                    participant_id: null,
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
                        component.form.course_id = null;
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