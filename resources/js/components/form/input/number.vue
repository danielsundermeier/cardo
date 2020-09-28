<template>
    <div>
        <label v-if="label">{{ label }}</label>
        <input class="form-control" :class="error ? 'is-invalid' : ''" type="text" v-model="display" @keydown.enter="$emit('enter', $event)">
        <div class="invalid-feedback" v-text="error ? error : ''"></div>
    </div>
</template>

<script>
    export default {

        props: {
            error: {
                type: String,
                required: true,
            },
            value: {
                required: true,
            },
            label: {
                type: String,
                required: false,
                default: '',
            }
        },

        computed: {
            display: {
                get() {
                    return this.value.format(this.value.neededDecimals(0, 2), ',', '');
                },
                set(value) {
                    this.$emit('input', this.parse(value));
                },
            },
        },

        data() {
            return {

            };
        },

        methods: {
            parse(value) {
                if (value == '') {
                    return 0;
                }
                var number = Number(value.replace(/\./g, '').replace(',', '.'));
                return number ? number : 0;
            },
        },

    };
</script>