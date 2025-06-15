<script setup lang="ts">
import DisableCustomerForm from "@/Forms/Customer/DisableCustomerForm.vue";
import DisableSiteForm from "@/Forms/Customer/DisableSiteForm.vue";
import Menu from "primevue/menu";
import Modal from "@/Components/_Base/Modal.vue";
import okModal from "@/Modules/okModal";
import VpnDataForm from "@/Forms/Customer/VpnDataForm.vue";
import { ref, computed, useTemplateRef, shallowRef } from "vue";
import { router } from "@inertiajs/vue3";
import {
    allowVpn,
    currentSite,
    customer,
    permissions,
    vpnData,
} from "@/Composables/Customer/CustomerData.module";

const menuList = useTemplateRef("customer-admin-menu");
const modal = useTemplateRef("disable-confirmation-modal");
const vpnModal = useTemplateRef("vpn-data-modal");
const warningMessage = ref<string>();
const formComponent = shallowRef();

/**
 * Determine if the "Disable Site" option is allowed.
 */
const isDisableSiteAllowed = (): boolean => {
    if (customer.value.site_count === 1) {
        return false;
    }

    if (!currentSite.value) {
        return false;
    }

    return true;
};

/**
 * Determine which management options should be shown
 */
const managementOptions = computed(() => {
    const options = [];

    if (
        allowVpn &&
        permissions.value.equipment.create &&
        vpnData.value == null
    ) {
        options.push({
            label: "Add VPN Data",
            command: () => vpnModal.value?.show(),
        });
    }

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

    if (permissions.value.details.create) {
        options.push({
            label: "Add Site",
            command: () =>
                router.get(
                    route("customers.sites.create", [
                        customer.value.slug,
                        currentSite.value.site_slug,
                    ])
                ),
        });
    }

    if (currentSite.value && permissions.value.details.update) {
        options.push({
            label: "Edit Site",
            command: () =>
                router.get(
                    route("customers.sites.edit", [
                        customer.value.slug,
                        currentSite.value.site_slug,
                    ])
                ),
        });
    }

    if (isDisableSiteAllowed() && permissions.value.details.delete) {
        options.push({
            label: "Disable Site",
            command: () => showDisableSite(),
        });
    }

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
 * Show the Disable Site Confirmation prompt and have customer verify response
 */
const showDisableSite = () => {
    if (customer.value.primary_site_id === currentSite.value.cust_site_id) {
        okModal(
            "You cannot disable the primary site.  Please assign another site as primary before disabling this site"
        );

        return;
    }

    formComponent.value = DisableSiteForm;
    warningMessage.value =
        "Disabling this site means that it will no longer be accessible";
    modal.value?.show();
};

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
            <component
                :is="formComponent"
                :customer="customer"
                :site="currentSite"
                class="mt-4"
                @success="modal?.hide()"
            />
        </Modal>
        <Modal ref="vpn-data-modal" title="Add VPN Data">
            <VpnDataForm :customer="customer" @success="vpnModal?.hide()" />
        </Modal>
    </div>
</template>
