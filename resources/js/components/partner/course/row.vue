<template>
    <tr>
        <td class="align-middle pointer" @click="show">{{ item.course.name }}</td>
        <td class="align-middle pointer" @click="show">{{ item.course.day_formatted }} {{ item.course.time_formatted }}</td>
        <template v-if="item.has_subscription">
            <td class="align-middle text-right d-none d-sm-table-cell">Abo</td>
            <td class="align-middle text-right">
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-secondary" title="Löschen" @click="destroy" v-if="item.is_deletable"><i class="fas fa-fw fa-trash"></i></button>
                </div>
            </td>
        </template>
        <template v-else-if="item.has_flatrate">
            <td class="align-middle text-right d-none d-sm-table-cell">Flatrate</td>
            <td class="align-middle text-right">
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-secondary" title="Löschen" @click="destroy"><i class="fas fa-fw fa-trash"></i></button>
                </div>
            </td>
        </template>
        <template v-else>
            <td class="align-middle text-right pointer" @click="show">{{ item.open_participations_count }}</td>
            <td class="align-middle text-right">
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-secondary" title="10er Karte kaufen" @click="create">+10</button>
                    <button type="button" class="btn btn-secondary" title="Löschen" @click="destroy" v-if="item.is_deletable"><i class="fas fa-fw fa-trash"></i></button>
                </div>
            </td>
        </template>
    </tr>
</template>

<script>
    export default {

        props: {
            item: {
                type: Object,
                required: true,
            },
            uri: {
                type: String,
                required: true,
            },
        },

        data () {
            return {
                id: this.item.id,
                errors: {},
                form: {
                    course_id: this.item.course_id,
                    create_invoice: true,
                },
            };
        },

        methods: {
            create() {
                var component = this;
                axios.post(component.uri, component.form)
                    .then( function (response) {
                        component.errors = {};
                        component.$emit('updated', response.data);
                    })
                    .catch(function (error) {
                        component.errors = error.response.data.errors;
                });
            },
            destroy() {
                axios.delete(this.uri + '/' + this.id);
                this.$emit('deleted', this.id);
            },
            edit() {
                location.href = this.item.course.edit_path;
            },
            show() {
                location.href = this.item.course.path;
            },
        },
    };
</script>