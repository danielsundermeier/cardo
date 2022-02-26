<template>
    <div v-if="isLoading" class="mt-3 p-5">
        <center>
            <span style="font-size: 48px;">
                <i class="fas fa-spinner fa-spin"></i><br />
            </span>
            Lade Daten
        </center>
    </div>
    <div class="mt-3 w-100 px-3" v-else>
        <div class="row flex-column text-center">
            <div v-if="workingtime == null">
                <button class="btn btn-success btn-lg mb-3" @click="create()">Arbeitszeit starten</button>
            </div>
            <div v-else>

                <p>Arbeitszeit seit: {{ workingtime.start_at_with_time_formatted }} ({{ workingtime.running_industry_hours_formatted }} h)<p>

                <div class="my-3">

                    <div class="form-check my-3">
                        <input class="form-check-input" type="radio" name="duration_break_in_seconds" id="duration_break_in_seconds1" value="0" v-model="form.duration_break_in_seconds">
                        <label class="form-check-label" for="duration_break_in_seconds1">
                            Keine Pause
                        </label>
                    </div>
                    <div class="form-check my-3">
                        <input class="form-check-input" type="radio" name="duration_break_in_seconds" id="duration_break_in_seconds2" value="900" v-model="form.duration_break_in_seconds">
                        <label class="form-check-label" for="duration_break_in_seconds2">
                            15 Minuten
                        </label>
                    </div>
                    <div class="form-check my-3">
                        <input class="form-check-input" type="radio" name="duration_break_in_seconds" id="duration_break_in_seconds3" value="1800" v-model="form.duration_break_in_seconds">
                        <label class="form-check-label" for="duration_break_in_seconds3">
                            30 Minuten
                        </label>
                    </div>
                    <div class="form-check my-3">
                        <input class="form-check-input" type="radio" name="duration_break_in_seconds" id="duration_break_in_seconds4" value="2700" v-model="form.duration_break_in_seconds">
                        <label class="form-check-label" for="duration_break_in_seconds4">
                            45 Minuten
                        </label>
                    </div>
                    <div class="form-check my-3">
                        <input class="form-check-input" type="radio" name="duration_break_in_seconds" id="duration_break_in_seconds5" value="3600" v-model="form.duration_break_in_seconds">
                        <label class="form-check-label" for="duration_break_in_seconds5">
                            60 Minuten
                        </label>
                    </div>

                </div>

                <button class="btn btn-danger btn-lg mb-3" @click="destroy()">Arbeitszeit beenden</button>

            </div>
        </div>
    </div>
</template>

<script>
    export default {

        components: {

        },

        props: {
            model: {
                type: Object,
                required: true,
            },
            selectedStaffId: {
                type: Number,
                required: true,
            },
        },

        data () {
            var date = (new Date),
                today_formatted = (new Date).toLocaleDateString('de-DE', {
                    month: '2-digit',
                    day: '2-digit',
                    year: 'numeric',
                });

            return {
                uri: this.model.path + '/workingtime',
                items: [],
                isLoading: true,
                filter: {
                    date: date.toISOString().split('T')[0],
                },
                form: {
                    duration_break_in_seconds: 1800,
                },
                duration_breaks_in_seconds: [
                    0,
                    900,
                    1800,
                    2700,
                    3600,
                ],
                workingtime: null,
                selected: [],
                errors: {},
            };
        },

        mounted() {

            this.fetch();

        },

        computed: {

        },

        methods: {
            create() {
                var component = this;
                axios.post(component.uri)
                    .then(function (response) {
                        component.workingtime = response.data;
                        Vue.success('Arbeitszeit gestartet.');
                    })
                    .catch( function (error) {
                        component.errors = error.response.data.errors;
                        // Vue.error('Interaktion konnte nicht erstellt werden!');
                });
            },
            destroy() {
                var component = this;
                axios.delete(component.uri + '/' + component.workingtime.id, {
                    data: component.form,
                })
                    .then(function (response) {
                        component.workingtime = null;
                        Vue.success('Arbeitszeit beendet.');
                    })
                    .catch( function (error) {
                        component.errors = error.response.data.errors;
                        // Vue.error('Interaktion konnte nicht erstellt werden!');
                });
            },
            fetch() {
                var component = this;
                component.isLoading = true;
                axios.get(component.uri, {
                    params: component.filter
                })
                    .then(function (response) {
                        component.workingtime = (response.data == '' ? null : response.data);
                        component.isLoading = false;
                    })
                    .catch(function (error) {
                        Vue.error('Datensatz konnten nicht geladen werden');
                        console.log(error);
                    });
            },
            updated(index, item) {
                Vue.set(this.items, index, item);
            },
            search() {
                this.filter.page = 1;
                this.fetch();
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