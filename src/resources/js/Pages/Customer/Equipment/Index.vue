<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import CustomerDetails from "@/Components/Customer/Show/CustomerDetails.vue";
import CustomerEquipment from "@/Components/Customer/Show/Equipment/CustomerEquipment.vue";
import { onMounted, onUnmounted } from "vue";
import { customer } from "@/Composables/Customer/CustomerData.module";
import {
    leaveCustomerChannel,
    registerCustomerChannel,
} from "@/Composables/Customer/CustomerBroadcasting.module";

/*
|-------------------------------------------------------------------------------
| Broadcasting Data
|-------------------------------------------------------------------------------
*/
const channelName = customer.value.slug;
onMounted(() => registerCustomerChannel(channelName));
onUnmounted(() => leaveCustomerChannel(channelName));
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div>
        <div class="flex gap-2 pb-2 border-b border-slate-400">
            <CustomerDetails class="grow" />
        </div>
        <div class="flex justify-center">
            <CustomerEquipment class="tb-card" />
        </div>
    </div>
</template>
