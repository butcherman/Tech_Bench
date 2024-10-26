<template>
    <div class="clearfix mb-3 me-3">
        <h5 v-if="siteList.length > 1">
            <span
                v-if="currentSite.cust_site_id === primarySiteId"
                class="text-success"
                title="Primary Site"
                v-tooltip
            >
                <fa-icon icon="paper-plane" />
            </span>
            {{ currentSite.site_name }}
        </h5>
        <address>
            <div class="float-start me-1 h-100 text-muted">
                <fa-icon icon="fa-map-marked" />
            </div>
            <a
                id="addr-span"
                :href="mapUrl"
                target="_blank"
                class="float-start ml-2 pointer text-muted"
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

<script setup lang="ts">
import { computed } from "vue";
import { currentSite, siteList } from "@/State/CustomerState";

defineProps<{
    primarySiteId: number;
}>();

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
