<script setup lang="ts">
import verifyModal from "@/Modules/verifyModal";
import { customer } from "@/Composables/Customer/CustomerData.module";
import { Menu } from "primevue";
import { router } from "@inertiajs/vue3";
import { useTemplateRef } from "vue";

const props = defineProps<{
    equipment: customerEquipment;
}>();

const menu = useTemplateRef("manage-equipment");

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
const managementOptions = [
    {
        label: "Disable Equipment",
        command: () => disableEquipment(),
    },
];
</script>

<template>
    <div>
        <button
            type="button"
            class="px-2"
            v-tooltip="'Manage Equipment'"
            @click="menu?.toggle"
        >
            <fa-icon icon="ellipsis-vertical" />
        </button>
        <Menu ref="manage-equipment" :model="managementOptions" popup />
    </div>
</template>
