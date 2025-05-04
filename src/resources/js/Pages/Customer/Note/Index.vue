<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import CustomerDetails from "@/Components/Customer/Show/CustomerDetails.vue";
import CustomerNotes from "@/Components/Customer/Show/Notes/CustomerNotes.vue";
import { customer } from "@/Composables/Customer/CustomerData.module";
import { onMounted, onUnmounted } from "vue";
import {
    leaveCustomerChannel,
    registerCustomerChannel,
} from "@/Composables/Customer/CustomerBroadcasting.module";

defineProps<{
    equipment?: customerEquipment;
}>();

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
        <div class="flex gap-2 pb-2 mb-2 border-b border-slate-400">
            <CustomerDetails class="grow" />
        </div>
        <CustomerNotes :equipment="equipment" />
    </div>
</template>
