<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import CustomerDetailsForm from "@/Forms/Customer/CustomerDetailsForm.vue";
import { customer, siteList } from "@/Composables/Customer/CustomerData.module";

defineProps<{
    selectId: boolean;
    defaultState: string;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div class="flex justify-center">
        <Card class="tb-card" title="Edit Customer">
            <CustomerDetailsForm
                :select-id="selectId"
                :default-state="defaultState"
                :customer="customer"
                :site-list="siteList"
            />
        </Card>
    </div>
</template>
