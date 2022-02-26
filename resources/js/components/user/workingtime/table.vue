<template>
    <div>
        <div class="row" v-if="false">
            <div class="col d-flex align-items-start mb-1 mb-sm-0">
                <div class="form-group mr-1 mb-0">
                    <input type="text" class="form-control" :class="'industry_hours_formatted' in errors ? 'is-invalid' : ''" v-model="form.industry_hours_formatted" @keydown.enter="update">
                    <div class="invalid-feedback" v-text="'industry_hours_formatted' in errors ? errors.industry_hours_formatted[0] : ''"></div>
                </div>
                <button class="btn btn-primary" @click="create(0)"><i class="fas fa-plus-square"></i></button>
            </div>
        </div>

        <div v-if="isLoading" class="mt-3 p-5">
            <center>
                <span style="font-size: 48px;">
                    <i class="fas fa-spinner fa-spin"></i><br />
                </span>
                Lade Daten
            </center>
        </div>
        <div class="table-responsive mt-3" v-else-if="items.length">
            <table class="table table-hover table-striped bg-white">
                <thead>
                    <tr>
                        <th width="100%">Datum</th>
                        <th class="text-right" width="100">Effektiv</th>
                        <th class="text-right" width="100">Aktion</th>
                    </tr>
                </thead>
                <tbody>
                    <row :item="item" :key="item.id" :uri="uri" v-for="(item, index) in items" @deleted="deleted(index)" @updated="updated(index, $event)"></row>
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td class="text-right font-weight-bold">{{ industryHoursSum.format(2, ',', '.') }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="alert alert-dark mt-3" v-else><center>Keine Daten vorhanden</center></div>
    </div>
</template>

<script>
    import row from "./row.vue";

    export default {

        components: {
            row,
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
            selectAll: {
                get: function () {
                    return this.items.length ? this.items.length == this.selected.length : false;
                },
                set: function (value) {
                    this.selected = [];
                    if (value) {
                        for (let i in this.items) {
                            this.selected.push(this.items[i].id);
                        }
                    }
                },
            },
        },

        methods: {
            create(industry_hours) {
                var component = this;
                component.form.industry_hours = industry_hours;
                axios.post(component.uri, component.form)
                    .then(function (response) {
                        component.errors = {};
                        component.items.unshift(response.data);
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