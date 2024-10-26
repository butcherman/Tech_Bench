<template>
    <div>
        <h3>{{ customer.name }}</h3>
        <h5 v-if="customer.dba_name">{{ customer.dba_name }}</h5>
        <CustomerSiteDetails
            v-if="currentSite"
            :primary-site-id="customer.primary_site_id"
        />
    </div>
</template>

<script setup lang="ts">
import CustomerSiteDetails from "./CustomerSiteDetails.vue";
import { customer, currentSite } from "@/State/CustomerState";
</script>
