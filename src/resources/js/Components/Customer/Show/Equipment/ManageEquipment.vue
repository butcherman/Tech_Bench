<script setup lang="ts">
import Modal from "@/Components/_Base/Modal.vue";
import verifyModal from "@/Modules/verifyModal";
import VpnDataForm from "@/Forms/Customer/VpnDataForm.vue";
import { computed, useTemplateRef } from "vue";
import { Menu } from "primevue";
import { router } from "@inertiajs/vue3";
import {
    allowVpn,
    allowWorkbook,
    customer,
    permissions,
    vpnData,
} from "@/Composables/Customer/CustomerData.module";

const props = defineProps<{
    equipment: customerEquipment;
}>();

const menu = useTemplateRef("manage-equipment");
const vpnModal = useTemplateRef("vpn-data-modal");

/**
 * Verify user wants to delete the equipment and then process result.
 */
const disableEquipment = () => {
    verifyModal("This Equipment will no longer be accessible").then((res) => {
        if (res) {
            router.delete(
                route("customers.equipment.destroy", [
                    customer.value.slug,
                    props.equipment.cust_equip_id,
                ])
            );
        }
    });
};

/**
 * Customer Equipment Management Options
 */
const managementOptions = computed(() => {
    const options = [];

    if (allowVpn.value && vpnData.value === null) {
        options.push({
            label: "Add VPN Data",
            command: () => vpnModal.value?.show(),
        });
    }

    if (allowWorkbook.value && !props.equipment.has_workbook) {
        options.push({
            label: "Add Workbook",
            command: () =>
                router.get(
                    route("customers.equipment.workbook.create", [
                        customer.value.slug,
                        props.equipment.cust_equip_id,
                    ])
                ),
        });
    }

    if (permissions.value.equipment.delete) {
        options.push({
            label: "Disable Equipment",
            command: () => disableEquipment(),
        });
    }

    return options;
});
</script>

<template>
    <div v-if="managementOptions.length">
        <button
            type="button"
            class="px-2"
            v-tooltip="'Manage Equipment'"
            @click="menu?.toggle"
        >
            <fa-icon icon="ellipsis-vertical" />
        </button>
        <Menu ref="manage-equipment" :model="managementOptions" popup />
        <Modal ref="vpn-data-modal" title="Add VPN Data">
            <VpnDataForm :customer="customer" @success="vpnModal?.hide()" />
        </Modal>
    </div>
</template>
