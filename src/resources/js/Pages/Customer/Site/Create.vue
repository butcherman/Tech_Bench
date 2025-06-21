<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import CustomerSiteForm from "@/Forms/Customer/CustomerSiteForm.vue";

defineProps<{
    defaultState: string;
    parentCustomer?: customer;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div class="flex justify-center">
        <Card class="tb-card">
            <CustomerSiteForm
                :default-state="defaultState"
                :parent-customer="parentCustomer"
            />
        </Card>
    </div>
</template>
