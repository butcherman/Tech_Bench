<script setup lang="ts">
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import AlertButton from "@/Components/_Base/Buttons/AlertButton.vue";
import AtomLoader from "@/Components/_Base/Loaders/AtomLoader.vue";
import Card from "@/Components/_Base/Card.vue";
import Overlay from "@/Components/_Base/Loaders/Overlay.vue";
import RefreshButton from "@/Components/_Base/Buttons/RefreshButton.vue";
import { customer, noteList } from "@/Composables/Customer/CustomerData.module";
import { computed, ref } from "vue";
import { Deferred } from "@inertiajs/vue3";
import {
    clearNotification,
    notificationStatus,
} from "@/Composables/Customer/CustomerBroadcasting.module";

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
    clearNotification("notes");
};

/*
|-------------------------------------------------------------------------------
| Other Methods
|-------------------------------------------------------------------------------
*/

/**
 * Determine which route the add button takes based on if there is a customer
 * site currently selected
 */
const addRoute = computed(() => {
    // if (props.equipment) {
    //     return route("customers.equipment.notes.create", [
    //         customer.value.slug,
    //         props.equipment.cust_equip_id,
    //     ]);
    // }

    // if (currentSite.value) {
    //     return route("customers.site.notes.create", [
    //         customer.value.slug,
    //         currentSite.value.site_slug,
    //     ]);
    // }

    return route("customers.notes.create", customer.value.slug);
});
</script>

<template>
    <Card>
        <template #title>
            <AlertButton v-if="notificationStatus.notes" />
            <RefreshButton
                flat
                :only="['noteList']"
                @loading-start="onRefreshStart"
                @loading-complete="onRefreshEnd"
            />
            Notes
            <AddButton
                class="float-end"
                size="small"
                text="Add Note"
                :href="addRoute"
                pill
            />
        </template>
        <Deferred data="noteList">
            <template #fallback>
                <div class="flex justify-center">
                    <AtomLoader />
                </div>
            </template>
            <Overlay :loading="isLoading" class="h-full">
                {{ noteList }}
            </Overlay>
        </Deferred>
    </Card>
</template>
