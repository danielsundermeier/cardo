<template>
    <tr>
        <td class="align-middle pointer">{{ item.participant.partner.name }}</td>
        <td class="align-middle pointer text-right">{{ item.participations_count }}</td>
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
                //
            };
        },

        methods: {
            //
        },
    };
</script>