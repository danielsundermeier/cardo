<template>

    <table-base :is-loading="isLoading" :items-length="items.length" :is-searchable="false" :has-filter="hasFilter()" @creating="create" @paginating="filter.page = $event" @searching="searching($event)">

        <template v-slot:form>
            <div class="form-group mb-0 mr-1">
                <select class="form-control form-control-sm" v-model="form.food_id">
                    <option :value="null">Nahrungsmittel auswählen</option>
                    <option :value="food.id" v-for="(food, index) in foods">{{ food.name }}</option>
                </select>
            </div>
        </template>

        <template v-slot:filter>

        </template>

        <template v-slot:no-data>
            <div class="form-group mb-0 mr-1">
                <select class="form-control form-control-sm" v-model="form.meal_id" @change="addMeal()">
                    <option :value="null">Mahlzeit auswählen</option>
                    <option :value="meal.id" v-for="(meal, index) in meals">{{ meal.name }}</option>
                </select>
            </div>
        </template>

        <template v-slot:thead>
            <tr>
                <th colspan="2" class="">Nahrungsmittel</th>
                <th class="text-right d-none d-sm-table-cell w-action">Aktion</th>
            </tr>
        </template>

        <template v-slot:tbody>
            <row :item="item" :key="item.id" :foods="foods" v-for="(item, index) in items" @deleted="deleted(index)" @updated="updated(index, $event)"></row>
        </template>

    </table-base>

</template>

<script>
    import row from './row.vue';
    import tableBase from '../../../../../tables/base.vue';
    import inputText from '../../../../../form/input/text.vue';

    import { baseMixin } from '../../../../../../mixins/tables/base.js';

    export default {

        components: {
            inputText,
            row,
            tableBase,
        },

        mixins: [
            baseMixin,
        ],

        props: {
            foods: {
                required: true,
                type: Array,
            },
            meals: {
                required: true,
                type: Array,
            },
            model: {
                required: true,
                type: Object,
            },
        },

        data () {

            return {
                filter: {
                    //
                },
                form: {
                    food_id: null,
                    meal_id: null,
                },
            };
        },

        computed: {

        },

        methods: {
            addMeal() {
                if (this.form.meal_id == null) {
                    return;
                }
                var component = this;
                axios.post(this.model.foods_from_meal_path, component.form)
                    .then(function (response) {
                        component.resetForm();
                        response.data.forEach( function (food) {
                            component.created(food);
                        });
                        Vue.successCreate(component.model);
                })
                    .catch(function (error) {
                        component.errors = error.response.data.errors;
                        Vue.errorCreate();
                });
            },
            resetForm() {
                this.resetErrors();
                this.form.food_id = null;
                this.form.meal_id = null;
            },
            created(item) {
                this.items.push(item);
                this.$emit('updated', this.items);
            },
            fetched(response) {
                this.items = response.data;
                this.$emit('updated', this.items);
            },
            deleted(index) {
                var item = this.items[index];
                this.items.splice(index, 1);
                Vue.successDelete(item);
                this.$emit('updated', this.items);
            },
            updated(index, item) {
                Vue.set(this.items, index, item);
                Vue.successUpdate(item);
                this.$emit('updated', this.items);
            },
        },
    };
</script>