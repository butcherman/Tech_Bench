<script setup lang="ts">
import { computed } from "vue";
import {
    currentSite,
    customer,
} from "@/Composables/Customer/CustomerData.module";

/**
 * Create a URL to go to Google Maps and address
 */
const mapUrl = computed<string>(() => {
    const uri = encodeURI(
        `${currentSite.value.address},${currentSite.value.city},${currentSite.value.state}`
    );
    return `https://www.google.com/maps/search/?api=1&query=${uri}`;
});
</script>

<template>
    <div>
        <h3 v-if="customer.site_count > 1">
            <span
                v-if="currentSite.cust_site_id === customer.primary_site_id"
                class="text-success"
                v-tooltip="'Primary Site'"
            >
                <fa-icon icon="paper-plane" />
            </span>
            {{ currentSite.site_name }}
        </h3>
        <address class="flex">
            <div class="me-1 text-muted">
                <fa-icon icon="fa-map-marked" />
            </div>
            <a
                id="addr-span"
                :href="mapUrl"
                target="_blank"
                class="ml-2 pointer text-muted"
                title="Click for Map"
                v-tooltip
            >
                {{ currentSite.address }}<br />
                {{ currentSite.city }},
                {{ currentSite.state }}
                &nbsp;{{ currentSite.zip }}
            </a>
        </address>
    </div>
</template>
