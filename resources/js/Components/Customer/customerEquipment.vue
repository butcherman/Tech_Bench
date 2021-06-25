<template>
    <div>
        <div class="card-title">
            Equipment:
            <new-equipment-modal
                v-if="permissions.create"
                :existing_equip="equipIdList"
                :cust_id="cust_id"
                @completed="getEquipment"
            ></new-equipment-modal>
        </div>
        <b-overlay :show="loading">
            <template #overlay>
                <atom-loader text="Loading Equipment..."></atom-loader>
            </template>
            <div v-if="equipment.length > 0">
                <div v-for="(equip, index) in equipment" :key="index" >
                    <b-card-header >
                        <b-button block variant="info" v-b-toggle="'equip-'+index">
                            <i v-if="equip.shared" class="fas fa-share" title="Equipment Shared Across Sites" v-b-tooltip.hover></i>
                            {{equip.name}}
                        </b-button>
                    </b-card-header>
                    <b-collapse :id="'equip-'+index" accordion="equipment-accordion" :visible="index === 0 ? true : false">
                        <b-card-body>
                            <b-table stacked small :items="getEquipData(equip.customer_equipment_data)"></b-table>
                            <div class="text-center">
                                <edit-equipment-modal
                                    v-if="permissions.update"
                                    :cust_id="cust_id"
                                    :data="equip.customer_equipment_data"
                                    :name="equip.name"
                                    :equip_id="equip.equip_id"
                                    :cust_equip_id="equip.cust_equip_id"
                                    :shared="equip.shared"
                                    :can_delete="permissions.delete"
                                    @completed="getEquipment"
                                ></edit-equipment-modal>
                            </div>
                        </b-card-body>
                    </b-collapse>
                </div>
            </div>
            <div v-else>
                <h5 class="text-center">No Equipment Has Been Assigned</h5>
            </div>
        </b-overlay>
    </div>
</template>

<script>
    import EditEquipmentModal from './Equipment/editEquipmentModal.vue';
    import newEquipmentModal  from './Equipment/newEquipmentModal.vue';

    export default {
        components: { newEquipmentModal, EditEquipmentModal },
        props: {
            customer_equipment: {
                type:     Array,
                required: true,
            },
            cust_id: {
                type:     Number,
                required: true,
            },
            permissions: {
                type:     Object,
                required: true,
            }
        },
        data() {
            return {
                equipment: this.customer_equipment,
                loading:   false,
            }
        },
        computed: {
            equipIdList()
            {
                var list = [];
                this.equipment.forEach(function(item)
                {
                    list.push(item.equip_id);
                });

                return list;
            }
        },
        methods: {
            getEquipData(data)
            {
                var dataList  = [];
                data.forEach(el => {
                    dataList[el.field_name] = el.value;
                });

                return [dataList];
            },
            getEquipment()
            {
                this.loading = true;
                axios.get(this.route('customers.equipment.show', this.cust_id))
                    .then(res => {
                        this.equipment = res.data;
                        this.loading = false;
                    }).catch(error => this.eventHub.$emit('axiosError', error));
            }
        },
    }
</script>
