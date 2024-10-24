<template>
    <div id="customer-wrapper">
        <Head :title="customer.name" />
        <div class="border-bottom border-secondary-subtle mb-2">
            <CustomerDetails />
        </div>
        <CustomerAlerts />
        <div class="row justify-content-center">
            <div class="col-md-8">
                <CustomerEquipment />
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import CustomerDetails from "@/Components/Customer/CustomerDetails.vue";
import CustomerAlerts from "@/Components/Customer/CustomerAlerts.vue";
import CustomerEquipment from "@/Components/Customer/CustomerEquipment.vue";
import { customer } from "@/State/CustomerState";
import { onMounted, onUnmounted } from "vue";
import { registerCustomerChannel } from "@/Modules/CustomerBroadcasting.module";

/**
 * Register to Customer Channel
 */
const channelName = customer.value.slug;
onMounted(() => {
    registerCustomerChannel(channelName);
});

/**
 * Leave Customer Channel
 */
onUnmounted(() => Echo.leave(`customer.${channelName}`));
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
