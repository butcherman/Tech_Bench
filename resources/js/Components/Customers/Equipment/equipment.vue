<template>
    <div>
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
                            <edit-equipment
                                v-if="permissions.update"
                                :cust_id="cust_id"
                                :equip="equip"
                                :allow_share="allow_share"
                            ></edit-equipment>
                            <delete-equipment
                                v-if="permissions.delete"
                                :cust_equip_id="equip.cust_equip_id"
                            ></delete-equipment>
                        </div>
                    </b-card-body>
                </b-collapse>
            </div>
        </div>
        <div v-else>
            <h5 class="text-center">No Equipment Has Been Assigned</h5>
        </div>
    </div>
</template>

<script>
    import DeleteEquipment from './deleteEquipment.vue';
    import editEquipment   from './editEquipment.vue';
    export default {
        components: { editEquipment, DeleteEquipment },
        props: {
            cust_id: {
                type:     Number,
                required: true,
            },
            equipment: {
                type:     Array,
                required: true,
            },
            permissions: {
                type:     Object,
                required: true,
            },
            allow_share: {
                type:     Boolean,
                default:  false,
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
        },
    }
</script>
