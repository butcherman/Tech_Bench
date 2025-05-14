<script setup lang="ts">
import AlertButton from "@/Components/_Base/Buttons/AlertButton.vue";
import Card from "@/Components/_Base/Card.vue";
import Overlay from "@/Components/_Base/Loaders/Overlay.vue";
import RefreshButton from "@/Components/_Base/Buttons/RefreshButton.vue";
import ResourceList from "@/Components/_Base/ResourceList.vue";
import { customer, siteList } from "@/Composables/Customer/CustomerData.module";
import { ref } from "vue";
import {
    clearNotification,
    notificationStatus,
} from "@/Composables/Customer/CustomerBroadcasting.module";

defineProps<{
    title?: string;
}>();

/**
 * Navigate to the selected site.
 */
const goToSite = (site: customerSite): string => {
    return route("customers.sites.show", [customer.value.slug, site.site_slug]);
};

/*
|-------------------------------------------------------------------------------
| Loading State
|-------------------------------------------------------------------------------
*/
const isLoading = ref<boolean>(false);

/**
 * Start the loading process when the refresh button clicked.
 */
const onRefreshStart = (): void => {
    isLoading.value = true;
};

/**
 * End the loading process and clear the alert icon.
 */
const onRefreshEnd = (): void => {
    isLoading.value = false;
    clearNotification("site");
};
</script>

<template>
    <Card v-if="siteList">
        <template #title>
            <span v-if="notificationStatus.site">
                <AlertButton tooltip="New Data Available.  Refresh to view" />
                <RefreshButton
                    :only="['siteList']"
                    flat
                    @loading-start="onRefreshStart"
                    @loading-complete="onRefreshEnd"
                />
            </span>
            {{ title ?? "Sites" }}
            <span class="float-end mb-1">
                <slot name="append-title" />
            </span>
        </template>
        <Overlay :loading="isLoading">
            <ResourceList
                :list="siteList"
                :link-fn="goToSite"
                :per-page="5"
                paginate
            >
                <template #list-item="{ item }">
                    {{ item.site_name }} ({{ item.city }})
                    <span
                        v-if="item.is_primary"
                        class="float-end text-blue-500"
                        v-tooltip.left="'Primary Site'"
                    >
                        <fa-icon icon="paper-plane" />
                    </span>
                </template>
            </ResourceList>
        </Overlay>
    </Card>
</template>
