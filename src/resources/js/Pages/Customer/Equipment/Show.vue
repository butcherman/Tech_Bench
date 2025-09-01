<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import CustomerAlerts from "@/Components/Customer/Show/CustomerAlerts.vue";
import CustomerDetails from "@/Components/Customer/Show/CustomerDetails.vue";
import CustomerFiles from "@/Components/Customer/Show/Files/CustomerFiles.vue";
import CustomerInfo from "@/Components/Customer/Show/CustomerInfo.vue";
import CustomerNotes from "@/Components/Customer/Show/Notes/CustomerNotes.vue";
import EquipmentData from "@/Components/Customer/Show/Equipment/EquipmentData.vue";
import EquipmentSites from "@/Components/Customer/Show/Equipment/EquipmentSites.vue";
import ManageEquipment from "@/Components/Customer/Show/Equipment/ManageEquipment.vue";
import VpnData from "@/Components/Customer/Show/Equipment/VpnData.vue";
import { onMounted, onUnmounted } from "vue";
import { allowVpn, customer } from "@/Composables/Customer/CustomerData.module";
import {
    leaveEquipmentChannel,
    registerEquipmentChannel,
} from "@/Composables/Customer/CustomerBroadcasting.module";

const props = defineProps<{
    equipment: customerEquipment;
    equipmentData: customerEquipmentData[];
}>();

/*
|-------------------------------------------------------------------------------
| Broadcasting Data
|-------------------------------------------------------------------------------
*/
const channelName = props.equipment.cust_equip_id;
onMounted(() => registerEquipmentChannel(channelName));
onUnmounted(() => leaveEquipmentChannel(channelName));
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div>
        <div class="flex gap-2 pb-2 border-b border-slate-400">
            <CustomerDetails class="grow" />
            <ManageEquipment :equipment="equipment" />
        </div>
        <CustomerInfo />
        <CustomerAlerts />
        <div class="flex flex-row-reverse my-1 gap-2">
            <VpnData v-if="allowVpn" />
            <BaseButton
                v-if="equipment.has_workbook"
                text="Show Workbook"
                size="small"
                pill
                :href="
                    $route('customers.equipment.workbook.index', [
                        customer.slug,
                        equipment.cust_equip_id,
                    ])
                "
            />
        </div>
        <EquipmentSites
            v-if="customer.site_count > 1"
            class="my-3"
            :equipment="equipment"
        />
        <EquipmentData
            class="my-3"
            :equipment="equipment"
            :equipment-data="equipmentData"
        />
        <CustomerNotes :equipment="equipment" />
        <CustomerFiles class="my-3" :equipment="equipment" />
    </div>
</template>
