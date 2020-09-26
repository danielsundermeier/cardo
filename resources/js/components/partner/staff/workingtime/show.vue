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
        <div class="row flex-column">
            <div class="alert alert-secondary" role="alert">
                Heutige Arbeitszeit: {{ industryHoursSum.format(2, ',', '.') }} h
            </div>
            <button class="btn btn-primary btn-lg mb-3" @click="create('1,00')">+1</button>
            <button class="btn btn-primary btn-lg mb-3" @click="create('0,50')">+0,5</button>
            <button class="btn btn-primary btn-lg mb-3" @click="create('0,25')">+0,25</button>
            <div class="form-group mb-1">
                <input type="text" class="form-control" :class="'industry_hours_formatted' in errors ? 'is-invalid' : ''" v-model="form.industry_hours_formatted" @keydown.enter="create(0)">
                <div class="invalid-feedback" v-text="'industry_hours_formatted' in errors ? errors.industry_hours_formatted[0] : ''"></div>
            </div>
            <button class="btn btn-primary btn-lg" @click="create(0)"><i class="fas fa-plus-square"></i></button>
        </div>
    </div>
</template>

<script>
    export default {

        components: {

        },

        props: {
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
                uri: '/workingtime',
                items: [],
                isLoading: true,
                filter: {
                    staff_id: this.selectedStaffId,
                    date: date.toISOString().split('T')[0],
                },
                form: {
                    staff_id: this.selectedStaffId,
                    industry_hours_formatted: '1,00',
                    start_at_formatted: today_formatted,
                },
                selected: [],
                errors: {},
            };
        },

        mounted() {

            this.fetch();

        },

        computed: {
            industryHoursSum() {
                var sum = 0;
                for( var index in this.items ) {
                    if( this.items.hasOwnProperty( index ) ) {
                        sum += parseFloat( this.items[index]['industry_hours'] );
                    }
                }
                return sum;
            },
        },

        methods: {
            create(industry_hours_formatted) {
                var component = this;
                if (industry_hours_formatted) {
                    component.form.industry_hours_formatted = industry_hours_formatted;
                }
                axios.post(component.uri, component.form)
                    .then(function (response) {
                        component.errors = {};
                        component.items.unshift(response.data);
                        component.form.industry_hours_formatted = '1,00';
                        Vue.success('Arbeitszeit gespeichert.');
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
                        component.items = response.data;
                        component.isLoading = false;
                    })
                    .catch(function (error) {
                        Vue.error('Datensätze konnten nicht geladen werden');
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