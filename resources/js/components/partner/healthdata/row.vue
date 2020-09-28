<template>
    <tr v-if="isEditing">
        <td class="align-middle pointer">
            <input class="form-control" :class="'at_formatted' in errors ? 'is-invalid' : ''" type="text" v-model="form.at_formatted" @keydown.enter="update">
            <div class="invalid-feedback" v-text="'at_formatted' in errors ? errors.at_formatted[0] : ''"></div>
        </td>
        <td class="align-middle pointer">
            <number-input v-model="form.weight_in_kg" :error="'weight_in_kg' in errors ? errors.weight_in_kg[0] : ''" @enter="update"></number-input>
        </td>
        <td class="align-middle pointer">{{ bmi }}</td>
        <td class="align-middle pointer">
            <number-input v-model="form.bloodpresure_systolic" :error="'bloodpresure_systolic' in errors ? errors.bloodpresure_systolic[0] : ''" @enter="update"></number-input>
        </td>
        <td class="align-middle pointer">
            <number-input v-model="form.bloodpresure_diastolic" :error="'bloodpresure_diastolic' in errors ? errors.bloodpresure_diastolic[0] : ''" @enter="update"></number-input>
        </td>
        <td class="align-middle pointer">
            <number-input v-model="form.heart_rate" :error="'heart_rate' in errors ? errors.heart_rate[0] : ''" @enter="update"></number-input>
        </td>
        <td class="align-middle pointer">
            <number-input v-model="form.resting_heart_rate" :error="'resting_heart_rate' in errors ? errors.resting_heart_rate[0] : ''" @enter="update"></number-input>
        </td>
        <td class="align-middle text-right">
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-primary" title="Speichern" @click="update"><i class="fas fa-fw fa-save"></i></button>
                <button type="button" class="btn btn-secondary" title="Abbrechen" @click="isEditing = false"><i class="fas fa-fw fa-times"></i></button>
            </div>
        </td>
    </tr>
    <tr v-else>
        <td class="align-middle pointer" @click="edit">{{ form.at_formatted }}</td>
        <td class="align-middle pointer" @click="edit">{{ form.weight_in_kg.format(2, ',', '.') }}</td>
        <td class="align-middle pointer" @click="edit">{{ bmi }}</td>
        <td class="align-middle pointer" @click="edit" colspan="2">{{ form.bloodpresure_systolic }}/{{ form.bloodpresure_diastolic }}</td>
        <td class="align-middle pointer" @click="edit">{{ form.heart_rate }}</td>
        <td class="align-middle pointer" @click="edit">{{ form.resting_heart_rate }}</td>
        <td class="align-middle text-right">
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-secondary" title="Bearbeiten" @click="edit"><i class="fas fa-fw fa-edit"></i></button>
                <button type="button" class="btn btn-secondary" title="Löschen" @click="destroy" v-if="item.is_deletable"><i class="fas fa-fw fa-trash"></i></button>
            </div>
        </td>
    </tr>
</template>

<script>
    import numberInput from '../../form/input/number.vue';

    export default {

        components: {
            numberInput,
        },

        props: [ 'item', 'uri', 'heightInCm' ],

        computed: {
            bmi() {
                if (this.height_in_m == 0 || this.form.weight_in_kg == 0) {
                    return 0;
                }

                return (this.form.weight_in_kg / (this.height_in_m * this.height_in_m) || 0).format(2, ',', '.');
            }
        },

        data () {
            return {
                id: this.item.id,
                type: this.item.type,
                isEditing: false,
                form: {
                    at_formatted: this.item.at_formatted,
                    weight_in_kg: this.item.weight_in_g / 1000,
                    bloodpresure_systolic: this.item.bloodpresure_systolic,
                    bloodpresure_diastolic: this.item.bloodpresure_diastolic,
                    heart_rate: this.item.heart_rate,
                    resting_heart_rate: this.item.resting_heart_rate,
                },
                errors: {},
                height_in_m: this.heightInCm / 100,
            };
        },

        methods: {
            destroy() {
                axios.delete(this.uri + '/' + this.id);
                this.$emit("deleted", this.id);
            },
            edit() {
                this.isEditing = true;
            },
            update() {
                var component = this;
                axios.put(this.uri + '/' + this.id, component.form)
                    .then( function (response) {
                        component.errors = {};
                        component.isEditing = false;
                        component.$emit("updated", response.data);
                    })
                    .catch(function (error) {
                        component.errors = error.response.data.errors;
                });
            },
        },
    };
</script>