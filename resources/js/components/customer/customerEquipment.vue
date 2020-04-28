<template>
    <div class="card">
        <div class="card-header">
            Customer Equipment:
            <b-button variant="primary" pill size="sm" class="float-right" @click="newEquipmentForm">
                <i class="fas fa-plus" aria-hidden="true"></i>
                Add Equipment
            </b-button>
        </div>
        <div class="card-body">
            <div v-if="error">
                <h5 class="text-center text-danger"><i class="fas fa-exclamation-circle"></i> Unable to load Equipment...</h5>
            </div>
            <div v-else-if="loading">
                <atom-spinner
                    :animation-duration="1000"
                    :size="60"
                    color="#ff1d5e"
                    class="mx-auto"
                />
                <h4 class="text-center">Loading Equipment</h4>
            </div>
            <div v-else-if="!equipment.length">
                <h4 class="text-center text-muted">No Equipment Assigned</h4>
            </div>
            <b-tabs v-else>
                <b-tab v-for="sys in equipment" :key="sys.cust_sys_id" :title="sys.sys_name" class="pt-2">
                    <dl class="row" v-for="data in sys.customer_system_data" :key="data.field_id">
                        <dt class="col-sm-6 text-sm-right mb-0">{{data.data_field_name}}:</dt>
                        <dd class="col-sm-6 text-sm-left mb-0">{{data.value}}</dd>
                    </dl>
                    <div class="text-center">
                        <b-button variant="warning" pill @click="editEquipmentForm(sys)" size="sm">
                            <i class="fas fa-pencil-alt"></i>
                            Edit Equipment
                        </b-button>
                    </div>
                </b-tab>
            </b-tabs>
        </div>
        <equipment-form ref="customer-equipment-form" :cust_id="cust_id" :existing_equip="existingEqiupArray" @completed="getEquipment"></equipment-form>
    </div>
</template>

<script>
export default {
    props: {
        cust_id: {
            type: Number,
            required: true,
        }
    },
    data() {
        return {
            error: false,
            loading: true,
            newEquip: false,
            equipment: {},
        }
    },
    mounted() {
        this.getEquipment();
    },
    computed: {
        existingEqiupArray()
        {
            var array = [];
            if(this.equipment.length)
            {
                this.equipment.forEach(function(item)
                {
                    array.push(item.sys_id);
                });
            }

            return array;
        }
    },
    methods: {
        //  Get all of the systems currently attached to the customer
        getEquipment()
        {
            this.loading = true;
            axios.get(this.route('customer.systems.show', this.cust_id))
                .then(res => {
                    this.equipment = res.data;
                    this.loading = false;
                }).catch(error => this.error = true);
        },
        //  Open the modal to edit the selected equipment
        editEquipmentForm(sys)
        {
            this.$refs['customer-equipment-form'].initEditEquip(sys);
        },
        //  Open the modal to assign new equipment
        newEquipmentForm()
        {
            this.$refs['customer-equipment-form'].initNewEquip();
        },
    }
}
</script>
