<template>
    <div id="customer-wrapper clearfix">
        <Head :title="customer.name" />
        <div class="border-bottom border-dark-subtle clearfix">
            <PinShowModel
                class="float-start me-2"
                :pin-name="customer.name"
                model-name="Customer"
                model-route="customers.show"
                :model-key="customer.slug"
                :active="isPinned"
            />
            <CustomerManagement v-if="showManagement" class="float-end" />
            <div class="float-start">
                <h3>{{ customer.name }}</h3>
                <h5 v-if="customer.dba_name">{{ customer.dba_name }}</h5>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import PinShowModel from "@/Components/_Base/PinShowModel.vue";
import CustomerManagement from "@/Components/Customer/CustomerManagement.vue";
import { customer, permissions } from "@/State/CustomerState";
import { computed } from "vue";

const props = defineProps<{
    isPinned: boolean;
}>();

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
