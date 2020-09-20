<template>
    <div class="mb-5">
        <create :model="model" @comment-created="created($event)" v-if="model != undefined"></create>
        <br />
        <div v-if="isLoading" class="p-5">
            <center>
                <span style="font-size: 48px;">
                    <i class="fas fa-spinner fa-spin"></i><br />
                </span>
                Lade Daten..
            </center>
        </div>
        <div class="list-group" v-else-if="items.length">
            <show v-for="(item, index) in items" :key="item.id" :item="item"></show>
        </div>
        <div class="alert alert-dark" v-else>
            <center>
                Keine Kommentare vorhanden
            </center>
        </div>
        <nav aria-label="Page navigation example">
            <ul class="pagination" v-show="paginate.lastPage > 1">
                <li class="page-item" v-show="paginate.prevPageUrl">
                    <a class="page-link" href="#" @click.prevent="page--">Previous</a>
                </li>

                <li class="page-item" v-for="n in paginate.lastPage" v-bind:class="{ active: (n == page) }"><a class="page-link" href="#" @click.prevent="page = n">{{ n }}</a></li>

                <li class="page-item" v-show="paginate.nextPageUrl">
                    <a class="page-link" href="#" @click.prevent="page++">Next</a>
                </li>
            </ul>
        </nav>
    </div>
</template>

<script>
    import create from "./create.vue";
    import show from "./show.vue";

    export default {

        components: {
            create,
            show,
        },

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

        data () {
            return {
                isLoading: true,
                items: [],
                paginate: {
                    nextPageUrl: null,
                    prevPageUrl: null,
                    lastPage: 0,
                },
                filter: {
                    page: 1,
                    searchtext: '',
                },
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
            created(event) {
                this.items.unshift(event.comment);
                // this.items.splice(-1,1);
            },
            fetch() {
                var component = this;
                component.isLoading = true;
                axios.get(component.model.path + '/comment')
                    .then(function (response) {
                        component.items = response.data.data;
                        component.filter.page = response.data.current_page;
                        component.paginate.nextPageUrl = response.data.next_page_url;
                        component.paginate.prevPageUrl = response.data.prev_page_url;
                        component.paginate.lastPage = response.data.last_page;
                        component.isLoading = false;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
        },

    };
</script>