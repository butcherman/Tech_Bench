<script setup lang="ts">
import Menu from "primevue/menu";
import { computed, useTemplateRef } from "vue";
import { concat } from "lodash";
import {
    customer,
    permissions,
} from "@/Composables/Customer/CustomerData.module";
import { router } from "@inertiajs/vue3";

const menuList = useTemplateRef("customer-admin-menu");

/**
 * Basic options to always be included in the menu
 */
const baseOptions = [
    {
        label: "Alerts",
        command: () =>
            router.get(route("customers.alerts.index", customer.value.slug)),
    },
    {
        label: "Deleted Items",
        command: () => console.log("deleted items"),
    },
];

/**
 * Site options will show if a specific site is being viewed
 */
const getSiteOptions = () => {
    let siteOptions = [];

    if (permissions.value.details.update) {
        siteOptions.push({
            label: "Edit Site",
            command: () => console.log("edit site"),
        });
    }

    if (permissions.value.details.delete) {
        siteOptions.push({
            label: "Disable Site",
            command: () => console.log("disable site"),
        });
    }

    return siteOptions;
};

/**
 * Customer options are at the end of the list and are always shown
 */
const getCustomerOptions = () => {
    let siteOptions = [];

    if (permissions.value.details.update) {
        siteOptions.push({
            label: "Edit Customer",
            command: () => console.log("edit customer"),
        });
    }

    if (permissions.value.details.delete) {
        siteOptions.push({
            label: "Disable Customer",
            command: () => console.log("disable customer"),
        });
    }

    return siteOptions;
};

/**
 * Determine which management options should be shown
 */
const managementOptions = computed(() => {
    return concat(baseOptions, getCustomerOptions());
});
</script>

<template>
    <div>
        <button
            type="button"
            class="px-2"
            v-tooltip="'Customer Management'"
            @click="menuList?.toggle"
        >
            <fa-icon icon="ellipsis-vertical" />
        </button>
        <Menu ref="customer-admin-menu" :model="managementOptions" popup />
    </div>
</template>
