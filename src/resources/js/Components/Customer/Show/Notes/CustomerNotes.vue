<script setup lang="ts">
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import AlertButton from "@/Components/_Base/Buttons/AlertButton.vue";
import AtomLoader from "@/Components/_Base/Loaders/AtomLoader.vue";
import Card from "@/Components/_Base/Card.vue";
import DataTable from "@/Components/_Base/DataTable/DataTable.vue";
import Overlay from "@/Components/_Base/Loaders/Overlay.vue";
import RefreshButton from "@/Components/_Base/Buttons/RefreshButton.vue";
import { customer, noteList } from "@/Composables/Customer/CustomerData.module";
import { computed, ref } from "vue";
import { Deferred, router } from "@inertiajs/vue3";
import {
    clearNotification,
    notificationStatus,
} from "@/Composables/Customer/CustomerBroadcasting.module";
import type { tableColumnProp } from "@/Components/_Base/DataTable/DataTable.vue";

const props = defineProps<{
    equipment?: customerEquipment;
}>();

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
| Table Data
|-------------------------------------------------------------------------------
*/
const columns: tableColumnProp[] = [
    {
        label: "Subject",
        field: "subject",
        filterable: true,
        filterPlaceholder: "Search Subject",
        sort: true,
    },
    {
        label: "Note Type",
        field: "note_type",
        filterable: true,
        filterSelect: true,
    },
    {
        label: "Last Updated",
        field: "updated_at",
        sort: true,
    },
];

const onRowClick = (row: customerNote): void => {
    console.log(row);

    if (props.equipment) {
        console.log("equipment note");
        return;
    }

    router.get(
        route("customers.notes.show", [customer.value.slug, row.note_id])
    );
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
    if (props.equipment) {
        console.log("equipment note");
        return "";
        //     return route("customers.equipment.notes.create", [
        //         customer.value.slug,
        //         props.equipment.cust_equip_id,
        //     ]);
    }

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
            <Overlay :loading="isLoading">
                <DataTable
                    :columns="columns"
                    :rows="noteList"
                    no-results-text="No Notes"
                    paginate
                    allow-row-click
                    striped
                    @row-click="onRowClick"
                />
            </Overlay>
        </Deferred>
    </Card>
</template>
