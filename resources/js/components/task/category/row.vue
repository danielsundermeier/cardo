<template>
    <tr v-if="isEditing">
        <td class="align-middle pointer">
            <input class="form-control" :class="'name' in errors ? 'is-invalid' : ''" type="text" v-model="form.name" @keydown.enter="update">
            <div class="invalid-feedback" v-text="'name' in errors ? errors.name[0] : ''"></div>
        </td>
        <td class="align-middle text-right">
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-primary" title="Speichern" @click="update"><i class="fas fa-fw fa-save"></i></button>
                <button type="button" class="btn btn-secondary" title="Abbrechen" @click="isEditing = false"><i class="fas fa-fw fa-times"></i></button>
            </div>
        </td>
    </tr>
    <tr v-else>
        <td class="align-middle pointer" @click="isEditing = true">{{ item.name }}</td>
        <td class="align-middle text-right">
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-secondary" title="Bearbeiten" @click="isEditing = true"><i class="fas fa-fw fa-edit"></i></button>
                <button type="button" class="btn btn-secondary" title="Löschen" @click="destroy" v-if="item.is_deletable"><i class="fas fa-fw fa-trash"></i></button>
            </div>
        </td>
    </tr>
</template>

<script>
    export default {

        props: {
            item: {
                type: Object,
                required: true,
            },
        },

        data () {
            return {
                id: this.item.id,
                isEditing: false,
                form: {
                    name: this.item.name,
                },
                errors: {},
            };
        },

        methods: {
            destroy() {
                axios.delete(this.item.path);
                this.$emit("deleted", this.id);
            },
            update() {
                var component = this;
                axios.put(this.item.path, component.form)
                    .then( function (response) {
                        component.errors = {};
                        component.isEditing = false;
                        component.$emit('updated', response.data);
                    })
                    .catch(function (error) {
                        component.errors = error.response.data.errors;
                });
            },
        },
    };
</script>