<template>
    <div class="card">
        <div class="card-header text-center text-md-left">
            Customer Equipment:
            <edit-customer-equipment class="float-md-right mt-2 mt-md-0" :cust_id="cust_id" :linked_site="linked_site" @equipment-updated="getEquipment"></edit-customer-equipment>
        </div>
        <div class="card-body">
            <div v-if="loading">
                <atom-spinner
                    :animation-duration="1000"
                    :size="60"
                    color="#ff1d5e"
                    class="mx-auto"
                />
                <h4 class="text-center">Loading...</h4>
            </div>
            <div v-else-if="error">
                <h5 class="text-center text-danger"><i class="fas fa-exclamation-circle"></i> Unable to load Equipment...</h5>
            </div>
            <div v-else-if="!equipment.length && !loading">
                <h4 class="text-center text-muted">No Equipment Assigned</h4>
            </div>
            <b-tabs v-else>
                <b-tab v-for="equip in equipment" :key="equip.cust_sys_id" :title="equip.sys_name">
                    <dl class="row" v-for="data in equip.customer_system_data" :key="data.field_id">
                        <dt class="col-sm-6 text-sm-right text-center mb-0">{{data.data_field_name}}:</dt>
                        <dd class="col-sm-6 text-sm-left text-center mb-0">{{data.value}}</dd>
                    </dl>
                    <div class="text-center">
                        <edit-customer-equipment :cust_id="cust_id" :equipment_data="equip" :linked_site="linked_site" @equipment-updated="getEquipment"></edit-customer-equipment>
                    </div>
                </b-tab>
            </b-tabs>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            cust_id: {
                type:     Number,
                required: true,
            },
            linked_site: {
                type:     Boolean,
                required: false,
                default:  false,
            }
        },
        data() {
            return {
                loading:   true,
                error:     false,
                equipment: [],
            }
        },
        created() {
            //
        },
        mounted() {
             this.getEquipment();
        },
        computed: {
             //
        },
        watch: {
             //
        },
        methods: {
            getEquipment()
            {
                this.loading = true;
                axios.get(this.route('customer.equipment.show', this.cust_id))
                    .then(res => {
                        console.log(res);
                        this.equipment = res.data;
                        this.loading = false;
                    }).catch(error => {this.error = true});
            }
        }
    }
</script>
