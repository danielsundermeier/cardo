<template>
    <tr v-if="edit">
        <td></td>
        <td class="align-middle pointer">
            <input class="form-control mb-1" :class="'name' in errors ? 'is-invalid' : ''" type="text" v-model="form.name">
            <div class="invalid-feedback" v-text="'name' in errors ? errors.name[0] : ''"></div>
            <textarea class="form-control" :class="'description' in errors ? 'is-invalid' : ''" v-model="form.description" rows="3"></textarea>
            <div class="invalid-feedback" v-text="'description' in errors ? errors.description[0] : ''"></div>
            <select class="form-control" :class="'partner_id' in errors ? 'is-invalid' : ''" v-model="form.partner_id" v-show="(item.item.course_id || item.item.is_flatrate)">
                <option :value="null">Kein Teilnehmer</option>
                <option :value="partner.id" v-for="partner in partners">{{ partner.name }}</option>
            </select>
            <div class="invalid-feedback" v-text="'partner_id' in errors ? errors.partner_id[0] : ''"></div>
        </td>
        <td class="align-middle pointer">
            <number-input v-model="form.quantity" :error="'quantity' in errors ? errors.quantity[0] : ''"></number-input>
        </td>
        <td class="align-middle pointer">
            <select class="form-control" :class="'unit_id' in errors ? 'is-invalid' : ''" v-model="form.unit_id">
                <option :value="unit.id" v-for="unit in units">{{ unit.name }}</option>
            </select>
            <div class="invalid-feedback" v-text="'unit_id' in errors ? errors.unit_id[0] : ''"></div>
        </td>
        <td class="align-middle pointer">
            <currency-input v-model="form.unit_price" :error="'unit_price' in errors ? errors.unit_price[0] : ''" :decimals="item.item.decimals"></currency-input>
        </td>
        <td class="align-middle pointer">
            <number-input v-model="form.discount" :error="'discount' in errors ? errors.discount[0] : ''"></number-input>
        </td>
        <td class="align-middle pointer" v-if="showTax">
            <select class="form-control" :class="'tax' in errors ? 'is-invalid' : ''" v-model="form.tax">
                <option :value="key" v-for="(label, key) in taxes">{{ label }}</option>
            </select>
            <div class="invalid-feedback" v-text="'tax' in errors ? errors.tax[0] : ''"></div>
        </td>
        <td class="align-middle text-right">{{ net }}</td>
        <td class="align-middle text-right">
            <div class="btn-group btn-group-sm" role="group">
                <button class="btn btn-secondary" title="Speichern" @click="update"><i class="fas fa-save"></i></button>
                <button type="button" class="btn btn-secondary" title="Abbrechen" @click="edit = false"><i class="fas fa-times"></i></button>
            </div>
        </td>
    </tr>
    <tr v-else>
        <td class="align-middle">
            <label class="form-checkbox"></label>
            <input :checked="selected" type="checkbox" :value="id"  @change="$emit('input', id)" number>
        </td>
        <td class="align-middle pointer" @click="edit = true">
            {{ item.name }}
            <div class="text-muted whitespace-pre" v-show="item.description" v-html="item.description"></div>
            <div class="text-muted" v-if="item.partner_id">Teilnehmer {{ item.partner.name }}</div>
        </td>
        <td class="align-middle pointer text-right" @click="edit = true">{{ item.quantity.format(2, ',', '.') }}</td>
        <td class="align-middle pointer" @click="edit = true">{{ item.unit ? item.unit.name : '' }}</td>
        <td class="align-middle pointer text-right" @click="edit = true">{{ item.unit_price.format(item.item.decimals, ',', '.') }} €</td>
        <td class="align-middle pointer text-right" @click="edit = true">{{ (item.discount * 100).format(1, ',', '') }}%</td>
        <td class="align-middle pointer text-right" @click="edit = true" v-if="showTax">{{ (item.tax * 100).format(0, ',', '') }}%</td>
        <td class="align-middle pointer text-right" @click="edit = true">{{ (item.net / 100).format(2, ',', '.') }} €</td>
        <td class="align-middle pointer text-right">
            <div class="btn-group btn-group-sm" role="group">
                <a :href="item.item.path"layouts.guest class="btn btn-secondary" title="Artikel" v-if="item.item_id > 0"><i class="fas fa-fw fa-eye"></i></a>
                <button class="btn btn-secondary" title="Bearbeiten" @click="edit = true"><i class="fas fa-edit"></i></button>
                <button type="button" class="btn btn-secondary" title="Löschen" @click="destroy"><i class="fas fa-trash"></i></button>
            </div>
        </td>
    </tr>
</template>

<script>
    import currencyInput from '../../form/input/currency.vue';
    import numberInput from '../../form/input/number.vue';

    export default {

        components: {
            currencyInput,
            numberInput,
        },

        props: [
            'item',
            'selected',
            'units',
            'company',
            'showTax',
            'partners',
        ],

        data () {
            return {
                id: this.item.id,
                edit: this.item.shouldEdit || false,
                form: {
                    description: this.item.description,
                    discount: Number(this.item.discount) * 100,
                    name: this.item.name,
                    quantity: Number(this.item.quantity),
                    tax: Number(this.item.tax),
                    unit_id: this.item.unit_id,
                    unit_price: Number(this.item.unit_price),
                    partner_id: this.item.partner_id,
                },
                errors: {},
                uri: '/bookkeeping/receipt/' + this.item.receipt_id + '/line/' + this.item.id,
                taxes: {
                    '0.19': '19%',
                    '0.16': '16%',
                    '0.07': '7%',
                    '0.00': '0%',
                },
            };
        },

        computed: {
            net() {
                return (this.form.quantity * this.form.unit_price * (1 - (this.form.discount / 100))).format(2, ',', '.');
            },
        },

        methods: {
            destroy() {
                var component = this;
                axios.delete(this.uri)
                    .then( function (response) {
                        component.$emit("deleted", component.id);
                    })
                    .catch(function (error) {
                        console.log(error);
                });
            },
            update() {
                var component = this;
                axios.put(this.uri, this.form)
                    .then( function (response) {
                        component.errors = {};
                        component.edit = false;
                        component.$emit('updated', response.data)
                    })
                    .catch(function (error) {
                        component.errors = error.response.data.errors;
                });
            },
        },
    };
</script>