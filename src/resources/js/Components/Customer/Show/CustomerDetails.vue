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
        <div>
            <CustomerSiteDetails v-if="currentSite" />
        </div>
    </div>
</template>
