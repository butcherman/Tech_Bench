<script setup lang="ts">
import DisableCustomerForm from "@/Forms/Customer/DisableCustomerForm.vue";
import Menu from "primevue/menu";
import Modal from "@/Components/_Base/Modal.vue";
import { ref, computed, useTemplateRef, shallowRef } from "vue";
import { router } from "@inertiajs/vue3";
import {
    customer,
    permissions,
} from "@/Composables/Customer/CustomerData.module";

const menuList = useTemplateRef("customer-admin-menu");
const modal = useTemplateRef("disable-confirmation-modal");
const warningMessage = ref<string>();
const formComponent = shallowRef();

/**
 * Determine which management options should be shown
 */
const managementOptions = computed(() => {
    // return concat(baseOptions, getCustomerOptions());
    const options = [];

    if (permissions.value.details.update) {
        options.push({
            label: "Alerts",
            command: () =>
                router.get(
                    route("customers.alerts.index", customer.value.slug)
                ),
        });
    }

    if (permissions.value.details.manage) {
        options.push({
            label: "Deleted Items",
            command: () =>
                router.get(
                    route("customers.deleted-items.index", customer.value.slug)
                ),
        });
    }

    // TODO - Add Site Option Stuff

    if (permissions.value.details.update) {
        options.push({
            label: "Edit Customer",
            command: () =>
                router.get(route("customers.edit", customer.value.slug)),
        });
    }

    if (permissions.value.details.delete) {
        options.push({
            label: "Disable Customer",
            command: () => showDisableCustomer(),
        });
    }

    return options;
});

/**
 * Show the Disable Customer Confirmation prompt and have customer verify response
 */
const showDisableCustomer = () => {
    formComponent.value = DisableCustomerForm;
    warningMessage.value =
        "Disabling this customer means that all sites and customer information will no longer be accessible.";
    modal.value?.show();
};
</script>

<template>
    <div>
        <button
            v-if="managementOptions.length"
            type="button"
            class="px-2"
            v-tooltip="'Customer Management'"
            @click="menuList?.toggle"
        >
            <fa-icon icon="ellipsis-vertical" />
        </button>
        <Menu ref="customer-admin-menu" :model="managementOptions" popup />
        <Modal ref="disable-confirmation-modal">
            <p class="text-center mt-2">{{ warningMessage }}</p>
            <component :is="formComponent" :customer="customer" class="mt-4" />
        </Modal>
    </div>
</template>
