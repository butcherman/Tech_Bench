<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import CustomerDetails from "@/Components/Customer/Show/CustomerDetails.vue";
import ManageEquipment from "@/Components/Customer/Show/Equipment/ManageEquipment.vue";
import EquipmentData from "@/Components/Customer/Show/Equipment/EquipmentData.vue";
import { permissions } from "@/Composables/Customer/CustomerData.module";

const props = defineProps<{
    equipment: customerEquipment;
    equipmentData: customerEquipmentData[];
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div>
        <div class="flex gap-2 pb-2 mb-2 border-b border-slate-400">
            <CustomerDetails class="grow" />
            <ManageEquipment
                v-if="permissions.equipment.delete"
                :equipment="equipment"
            />
        </div>
        <EquipmentData :equipment="equipment" :equipment-data="equipmentData" />
        {{ equipmentData[3] }}
    </div>
</template>
