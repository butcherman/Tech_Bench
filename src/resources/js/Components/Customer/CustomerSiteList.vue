<template>
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <span class="float-start">
                    <slot name="status">
                        <span v-if="changeAlert.site">
                            <AlertButton
                                title="Site information Updated.  Refresh for most recent data"
                            />
                            <RefreshButton
                                :only="['siteList']"
                                @loading-start="toggleLoading('site')"
                                @loading-complete="clearAlert('site')"
                            />
                        </span>
                    </slot>
                </span>
                <span class="float-end">
                    <slot name="add-button" />
                </span>
                <div class="mt-1">{{ titleText || "Sites" }}:</div>
            </div>
            <Overlay :loading="loading.site">
                <Table
                    :columns="columns"
                    :rows="siteList"
                    :paginate="siteList.length > 10"
                    responsive
                    rowClickable
                    :no-results-text="noResultsText"
                >
                    <template #action="{ rowData }">
                        <span
                            v-if="rowData.is_primary"
                            class="text-primary"
                            title="Primary Site"
                            v-tooltip
                        >
                            <fa-icon icon="paper-plane" />
                        </span>
                    </template>
                </Table>
            </Overlay>
        </div>
    </div>
</template>

<script setup lang="ts">
import Table from "../_Base/Table.vue";
import Overlay from "../_Base/Loaders/Overlay.vue";
import AlertButton from "../_Base/Buttons/AlertButton.vue";
import RefreshButton from "../_Base/Buttons/RefreshButton.vue";
import { computed } from "vue";
import {
    siteList,
    changeAlert,
    clearAlert,
    loading,
    toggleLoading,
} from "@/State/CustomerState";

const props = defineProps<{
    titleText?: string;
    emptyText?: string;
}>();

const noResultsText = computed(
    () =>
        props.emptyText ||
        "No Sites attached to this Customer.  Please add one."
);

const columns = [
    {
        label: "Site Name",
        field: "site_name",
    },
    {
        label: "City",
        field: "city",
    },
];
</script>
