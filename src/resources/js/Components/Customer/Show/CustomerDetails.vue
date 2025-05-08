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
        <h3 v-if="currentSite">{{ currentSite.site_name }}</h3>
        <h5 v-if="customer.dba_name" class="text-muted">
            {{ customer.dba_name }}
        </h5>
        <div class="flex gap-4 text-muted text-sm">
            <div>
                <span class="font-semibold"> Customer ID: </span>
                {{ customer.cust_id }}
            </div>
            <div>|</div>
            <div v-if="currentSite">
                <span class="font-semibold"> Site ID: </span>
                {{ currentSite.cust_site_id }}
            </div>
        </div>
        <CustomerSiteDetails v-if="currentSite" />
    </div>
</template>
