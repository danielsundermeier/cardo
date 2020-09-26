<template>
    <div>
        <div class="row">
            <div class="col d-flex align-items-start mb-1 mb-sm-0">
                <div class="form-group mb-0 mr-1">
                    <select class="form-control" :class="{'is-invalid': ('staff_id' in errors)}" v-model="form.staff_id">
                        <option :value="partner.id" v-for="partner in partners">{{ partner.name }}</option>
                    </select>
                    <div class="invalid-feedback" v-text="'staff_id' in errors ? errors.staff_id[0] : ''"></div>
                </div>
                <div class="form-group mr-1 mb-0">
                    <input type="text" class="form-control" :class="'start_at_formatted' in errors ? 'is-invalid' : ''" v-model="form.start_at_formatted" @keydown.enter="create">
                    <div class="invalid-feedback" v-text="'start_at_formatted' in errors ? errors.start_at_formatted[0] : ''"></div>
                </div>
                <div class="form-group mr-1 mb-0">
                    <input type="text" class="form-control" :class="'industry_hours_formatted' in errors ? 'is-invalid' : ''" v-model="form.industry_hours_formatted" @keydown.enter="create">
                    <div class="invalid-feedback" v-text="'industry_hours_formatted' in errors ? errors.industry_hours_formatted[0] : ''"></div>
                </div>
                <button class="btn btn-primary" @click="create(0)"><i class="fas fa-plus-square"></i></button>
            </div>
            <div class="col-auto d-flex">
                <div class="form-group" style="margin-bottom: 0;" v-if="false">
                    <filter-search v-model="filter.searchtext" @input="fetch()"></filter-search>
                </div>
                <button class="btn btn-secondary ml-1" @click="filter.show = !filter.show"><i class="fas fa-filter"></i></button>
            </div>
        </div>

        <form v-if="filter.show" id="filter" class="mt-1">
            <div  class="form-row">

                <div class="col-12 col-md-3">
                    <div class="form-group">
                        <label for="filter-staff">Personal</label>
                        <select class="form-control" id="filter-staff" v-model="filter.staff_id" @change="search">
                            <option :value="null">Alle</option>
                            <option :value="partner.id" v-for="partner in partners">{{ partner.name }}</option>
                        </select>
                    </div>
                </div>

                <div class="col-12 col-md-3">
                    <div class="form-group">
                        <label for="filter-staff">Monat</label>
                        <select class="form-control" id="filter-staff" v-model="filter.month" @change="search">
                            <option :value="null">Alle</option>
                            <option :value="index" v-for="(month, index) in months">{{ month }}</option>
                        </select>
                    </div>
                </div>

                <div class="col-12 col-md-3">
                    <div class="form-group">
                        <label for="filter-staff">Jahr</label>
                        <select class="form-control" id="filter-staff" v-model="filter.year" @change="search">
                            <option :value="null">Alle</option>
                            <option :value="year" v-for="(year, index) in years">{{ year }}</option>
                        </select>
                    </div>
                </div>

            </div>
        </form>

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
                        <th width="100%">Personal</th>
                        <th width="200">Datum</th>
                        <th class="text-right" width="100">Dauer</th>
                        <th class="text-right" width="100">Aktion</th>
                    </tr>
                </thead>
                <tbody>
                    <row :item="item" :key="item.id" :uri="uri" :partners="partners" v-for="(item, index) in items" @deleted="deleted(index)" @updated="updated(index, $event)"></row>
                </tbody>
                <tfoot>
                    <tr class="font-weight-bold" v-for="industryHoursSum in industryHoursSums">
                        <td>{{ industryHoursSum.name }}</td>
                        <td></td>
                        <td class="text-right">{{ industryHoursSum.sum.format(2, ',', '.') }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="alert alert-dark mt-3" v-else><center>Keine Daten vorhanden</center></div>
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center" v-show="paginate.lastPage > 1">
                <li class="page-item" v-show="paginate.prevPageUrl">
                    <a class="page-link" href="#" @click.prevent="filter.page--">zurück</a>
                </li>

                <li class="page-item" v-for="(n, i) in pages" v-bind:class="{ active: (n == filter.page) }"><a class="page-link" href="#" @click.prevent="filter.page = n">{{ n }}</a></li>

                <li class="page-item" v-show="paginate.nextPageUrl">
                    <a class="page-link" href="#" @click.prevent="filter.page++">weiter</a>
                </li>
            </ul>
        </nav>
    </div>
</template>

<script>
    import row from "./row.vue";
    import filterSearch from "../filter/search.vue";

    export default {

        components: {
            filterSearch,
            row,
        },

        props: {
            partners: {
                type: Array,
                required: true,
            },
            months: {
                type: Array,
                required: true,
            },
            years: {
                type: Array,
                required: true,
            },
            selectedStaffId: {
                type: Number,
                required: true,
            },
        },

        data () {
            var today = (new Date);

            return {
                uri: '/workingtime',
                items: [],
                isLoading: true,
                paginate: {
                    nextPageUrl: null,
                    prevPageUrl: null,
                    lastPage: 0,
                },
                filter: {
                    page: 1,
                    searchtext: '',
                    staff_id: null,
                    year: today.getFullYear(),
                    month: today.getMonth() + 1,
                    show: false,
                },
                form: {
                    staff_id: this.selectedStaffId,
                    industry_hours_formatted: '1,00',
                    start_at_formatted: today.toLocaleDateString('de-DE', {
                        month: '2-digit',
                        day: '2-digit',
                        year: 'numeric',
                    }),
                },
                selected: [],
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

        computed: {
            industryHoursSums() {
                var sums = {};
                for( var index in this.items ) {
                    if( this.items.hasOwnProperty( index ) ) {
                        if( ! sums.hasOwnProperty( this.items[index]['staff_id'] ) ) {
                            sums[this.items[index]['staff_id']] = {
                                name: this.items[index]['partner']['name'],
                                sum: 0,
                            };
                        }
                        sums[this.items[index]['staff_id']].sum += parseFloat( this.items[index]['industry_hours'] );
                    }
                }
                return sums;
            },
            page() {
                return this.filter.page;
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
            pages() {
                var pages = [];
                for (var i = 1; i <= this.paginate.lastPage; i++) {
                    if (this.showPageButton(i)) {
                        const lastItem = pages[pages.length - 1];
                        if (lastItem < (i - 1) && lastItem != '...') {
                            pages.push('...');
                        }
                        pages.push(i);
                    }
                }

                return pages;
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
                        component.items = response.data.data;
                        component.filter.page = response.data.current_page;
                        component.paginate.nextPageUrl = response.data.next_page_url;
                        component.paginate.prevPageUrl = response.data.prev_page_url;
                        component.paginate.lastPage = response.data.last_page;
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
            showPageButton(page) {
                if (page == 1 || page == this.paginate.lastPage) {
                    return true;
                }

                if (page <= this.filter.page + 2 && page >= this.filter.page - 2) {
                    return true;
                }

                return false;
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