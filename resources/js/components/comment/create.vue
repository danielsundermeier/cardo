<template>
    <div>
        <div class="form-group">
            <label for="text">Kommentar</label>
            <textarea class="form-control" id="text" rows="3" v-model="form.text"></textarea>
        </div>

        <button class="btn btn-primary" @click="create">Kommentieren</button>
    </div>
</template>

<script>
    export default {

        props: {
            model: {
                type: Object,
                required: true,
            }
        },

        data () {
            return {
                form: {
                    text: '',
                }
            };
        },

        methods: {
            create() {
                var component = this;
                axios.post(component.model.path + '/comment', component.form)
                    .then(function (response) {
                        component.$emit('comment-created', {
                            comment: response.data
                        });
                        component.text = '';
                    });
            },
        }

    };
</script>