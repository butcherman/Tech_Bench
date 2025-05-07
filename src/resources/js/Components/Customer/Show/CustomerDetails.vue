<script setup lang="ts">
import CustomerSiteDetails from "./CustomerSiteDetails.vue";
import {
    currentSite,
    customer,
} from "@/Composables/Customer/CustomerData.module";
</script>

<template>
    <div>
        <h1>{{ customer.name }}</h1>
        <h5 v-if="customer.dba_name" class="text-muted">
            {{ customer.dba_name }}
        </h5>
        <div class="text-muted text-sm">
            <span class="font-semibold"> Customer ID: </span>
            {{ customer.cust_id }}
        </div>
        <CustomerSiteDetails v-if="currentSite" />
    </div>
</template>
