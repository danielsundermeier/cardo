<template>
    <tr v-if="isEditing">
        <td class="align-middle pointer">
            {{ item.start_at_formatted }}
            <div class="text-muted" v-if="item.course_date_id"><a :href="item.date.course.path">{{ item.date.course.name }}</a> am <a :href="item.date.path">{{ item.date.at_formatted }}</a></div>
        </td>
        <td class="align-middle pointer">
            <input type="text" class="form-control" :class="'industry_hours_formatted' in errors ? 'is-invalid' : ''" v-model="form.industry_hours_formatted" @keydown.enter="update">
            <div class="invalid-feedback" v-text="'industry_hours_formatted' in errors ? errors.industry_hours_formatted[0] : ''"></div>
        </td>
        <td class="align-middle text-right">
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-primary" title="Speichern" @click="update"><i class="fas fa-fw fa-save"></i></button>
                <button type="button" class="btn btn-secondary" title="Abbrechen" @click="isEditing = false"><i class="fas fa-fw fa-times"></i></button>
            </div>
        </td>
    </tr>
    <tr :class="{'text-success': item.running_industry_hours > 0}" v-else>
        <td class="align-middle pointer">
            {{ item.start_at_formatted }}
            <div class="text-muted" v-if="item.course_date_id"><a :href="item.date.course.path">{{ item.date.course.name }}</a> am <a :href="item.date.path">{{ item.date.at_formatted }}</a></div>
        </td>
        <td class="align-middle pointer text-right">{{ item.running_industry_hours > 0 ? item.running_industry_hours_formatted : item.effective_industry_hours_formatted }}</td>
        <td class="align-middle text-right">
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-secondary" title="Löschen" @click="destroy" v-if="item.is_deletable"><i class="fas fa-trash"></i></button>
            </div>
        </td>
    </tr>
</template>

<script>
    export default {

        components: {

        },

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
                errors: {},
                form: {
                    staff_id: this.item.staff_id,
                    industry_hours_formatted: this.item.industry_hours_formatted,
                    start_at_formatted: this.item.start_at_formatted,
                },
                id: this.item.id,
                isEditing: false,
            };
        },

        methods: {
            destroy() {
                var component = this;
                axios.delete(component.item.path)
                    .then(function (response) {
                        if (response.data.deleted) {
                            component.$emit("deleted", component.id);
                            Vue.success('Datensatz gelöscht.');
                        }
                        else {
                            Vue.error('Datensatz konnte nicht gelöscht werden!');
                        }
                    });
            },
            edit() {
                if (! this.item.is_editable) {
                    return;
                }
                this.isEditing = true;
            },
            show() {
                location.href = this.item.path;
            },
            update() {
                var component = this;
                axios.put(component.item.path, component.form)
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