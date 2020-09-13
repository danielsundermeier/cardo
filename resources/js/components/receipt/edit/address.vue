<template>
    <div>
        <div class="form-group">
            <label for="partner_id">Kunde <a class="ml-1 pointer" :href="selectedPartner.path"><i class="fas fa-external-link-alt"></i></a></label>
            <select class="form-control" v-model="partner_id" @change="fetchAddress" name="partner_id">
                <option v-for="partner in partners" :value="partner.id">{{ partner.name }}</option>
            </select>
        </div>

        <div class="form-group">
            <label for="address">Adresse</label>
            <textarea class="form-control" rows="4" v-model="address" name="address"></textarea>
        </div>
    </div>
</template>

<script>
    export default {

        props: [
            'partners',
            'selectedPartnerId',
            'selectedAddress',
        ],

        data () {
            return {
                partner_id: this.selectedPartnerId,
                address: this.selectedAddress,
                selectedPartner: {},
            };
        },

        mounted() {
            this.findSelectedPartner();
        },

        methods: {
            fetchAddress() {
                var component = this;
                component.findSelectedPartner();
                component.address = component.selectedPartner.billing_address;
            },
            findSelectedPartner() {
                var component = this;
                this.partners.forEach( function (partner, index) {
                    if (partner.id == component.partner_id) {
                        component.selectedPartner = partner;
                    }
                });
            },
        },

    };
</script>