<template>
   <div class="card">
    <div class="card-body">
        <div class="card-title clearfix">
            Equipment
            <new-equipment v-if="customerStore.userPerm.equipment.create" />
        </div>
        <div v-if="customerStore.equipment.length">
            <div
                v-for="(equip, index) in customerStore.equipment"
                :key="index"
            >
                <b-card-header >
                    <b-button
                        variant="info"
                        v-b-toggle="`equip-${index}`"
                        block
                    >
                        <i
                            v-if="equip.shared"
                            class="fas fa-share"
                            title="Equipment Shared Across Sites"
                            v-b-tooltip.hover
                        />
                        {{equip.name}}
                    </b-button>
                </b-card-header>
                <b-collapse
                    :id="`equip-${index}`"
                    :visible="index === 0"
                    accordion="equipment-accordion"
                >
                    <b-card-body>
                        <b-table
                            :items="getEquipData(equip.customer_equipment_data)"
                            small
                            stacked
                        />
                        <div class="text-center">
                            <edit-equipment
                                v-if="customerStore.userPerm.equipment.update"
                                :equip-index="index"
                            />
                            <delete-equipment
                                v-if="customerStore.userPerm.equipment.delete"
                                :equip-index="index"
                            />
                        </div>
                    </b-card-body>
                </b-collapse>
            </div>
        </div>
    </div>
   </div>
</template>

<script>
    import { useCustomerStore } from '../../../../Stores/customerStore';
    import { mapStores }        from 'pinia';

    export default {
        computed: {
            ...mapStores(useCustomerStore),
        },
        methods: {
            getEquipData(data)
            {
                let dataList  = [];
                data.forEach(el => {
                    dataList[`${el.field_name}:`] = el.value;
                });

                return [dataList];
            },
        },
    }
</script>
