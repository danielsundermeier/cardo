<template>
    <div>
        <select class="form-control form-control-sm" :class="error ? 'is-invalid' : ''" id="filter-active" v-model="value" @change="$emit('input', value)">
            <option :value="null">Aktive und inaktive {{ label }}</option>
            <option :value="1">Aktive {{ label }}</option>
            <option :value="0">Inaktive {{ label }}</option>
        </select>
        <div class="invalid-feedback" v-text="error ? error : ''"></div>
    </div>
</template>

<script>
    export default {

        props: {
            error: {
                type: String
            },
            label: {
                required: false,
                type: String,
                default: 'Datensätze'
            }
        },

        data() {
            return {
                value: 1,
            };
        },

        methods: {
            //
        },

    };
</script>