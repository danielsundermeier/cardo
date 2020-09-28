<template>
    <div>
        <div class="row">
            <div class="col-12 col-lg-6">

                <div class="card mb-3">
                    <div class="card-header">Gesundheitsdaten</div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Datum</label>
                            <input class="form-control" :class="'at_formatted' in errors ? 'is-invalid' : ''" type="text" v-model="form.at_formatted" @keydown.enter="update">
                            <div class="invalid-feedback" v-text="'at_formatted' in errors ? errors.at_formatted[0] : ''"></div>
                        </div>
                        <number-input class="form-group" v-model="form.weight_in_kg" label="Gewicht" :error="'weight_in_kg' in errors ? errors.weight_in_kg[0] : ''" @enter="update"></number-input>
                        <number-input class="form-group" v-model="form.bloodpresure_systolic" label="Blutdruck" :error="'bloodpresure_systolic' in errors ? errors.bloodpresure_systolic[0] : ''" @enter="update"></number-input>
                        <number-input class="form-group" v-model="form.bloodpresure_diastolic" label="Blutdruck" :error="'bloodpresure_diastolic' in errors ? errors.bloodpresure_diastolic[0] : ''" @enter="update"></number-input>
                        <number-input class="form-group" v-model="form.heart_rate" label="Puls" :error="'heart_rate' in errors ? errors.heart_rate[0] : ''" @enter="update"></number-input>
                        <number-input class="form-group" v-model="form.resting_heart_rate" label="Ruhepuls" :error="'resting_heart_rate' in errors ? errors.resting_heart_rate[0] : ''" @enter="update"></number-input>
                    </div>
                </div>

            </div>

            <div class="col-12 col-lg-6">

                <div class="card mb-3">
                    <div class="card-header">Beschwerden</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col d-flex align-items-start mb-1 mb-sm-0">
                                <div class="form-group">
                                    <input class="form-control" :class="'bodypart' in errors ? 'is-invalid' : ''" type="text" v-model="formComplain.bodypart" placeholder="Körperregion" @keydown.enter="update">
                                    <div class="invalid-feedback" v-text="'bodypart' in errors ? errors.bodypart[0] : ''"></div>
                                </div>
                                <div class="form-group ml-1">
                                    <input class="form-control" :class="'complain' in errors ? 'is-invalid' : ''" type="text" v-model="formComplain.complain" placeholder="Beschwerde" @keydown.enter="update">
                                    <div class="invalid-feedback" v-text="'complain' in errors ? errors.complain[0] : ''"></div>
                                </div>
                                <div class="form-group ml-1" style="margin-bottom: 0;">
                                    <button class="btn btn-primary" title="Anlegen" @click="createComplain"><i class="fas fa-plus-square"></i></button>
                                </div>
                            </div>
                            <div class="col-auto d-flex">

                            </div>
                        </div>
                        <table class="table table-striped table-sm" v-show="form.data.complains.length">
                            <thead>
                                <tr>
                                    <th width="50%">Körperregion</th>
                                    <th width="50%">Beschwerde</th>
                                    <th width="100"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(complain, index) in form.data.complains">
                                    <td>{{ complain.bodypart }}</td>
                                    <td>{{ complain.complain }}</td>
                                    <td class="text-right">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" class="btn btn-secondary" title="Löschen" @click="destroyComplain(index)"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>

        <div class="row mb-3">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <button class="btn btn-primary" @click="update">Speichern</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    import numberInput from '../../form/input/number.vue';

    export default {

        components: {
            numberInput,
        },

        props: {
            model: {
                type: Object,
                required: true,
            },
        },

        data () {
            return {
                errors: {},
                form: {
                    at_formatted: this.model.at_formatted,
                    bloodpresure_diastolic: this.model.healthdata.bloodpresure_diastolic,
                    bloodpresure_systolic: this.model.healthdata.bloodpresure_systolic,
                    heart_rate: this.model.healthdata.heart_rate,
                    resting_heart_rate: this.model.healthdata.resting_heart_rate,
                    weight_in_kg: this.model.healthdata.weight_in_g / 1000,
                    data: this.model.filled_data,
                },
                formComplain: {
                    bodypart: '',
                    complain: '',
                },
            };
        },

        methods: {
            createComplain() {
                this.form.data.complains.unshift({
                    bodypart: this.formComplain.bodypart,
                    complain: this.formComplain.complain,
                });
                this.formComplain.bodypart = '';
                this.formComplain.complain = '';
            },
            destroyComplain(index) {
                this.form.data.complains.splice(index, 1);
            },
            update() {
                var component = this;
                axios.put(component.model.path, component.form)
                    .then( function (response) {
                        component.errors = {};
                        Vue.success('Datensatz gespeichert.');
                    })
                    .catch(function (error) {
                        component.errors = error.response.data.errors;
                        Vue.error('Datensatz konnte nichtgespeichert werden.');
                });
            },
        },
    };
</script>