<template>
    <div id="customer-wrapper">
        <Head :title="customer.name" />
        <div class="border-bottom border-secondary-subtle">
            <CustomerManagement v-if="showManagement" class="float-end" />
            <CustomerDetails />
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import CustomerManagement from "@/Components/Customer/CustomerManagement.vue";
import CustomerDetails from "@/Components/Customer/CustomerDetails.vue";
import { ref, computed, onMounted } from "vue";
import { customer, siteList, permissions } from "@/State/CustomerState";

const showManagement = computed(
    () =>
        permissions.value.details.update ||
        permissions.value.details.manage ||
        permissions.value.details.delete
);
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
