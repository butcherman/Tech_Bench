<template>
    <div id="customer-wrapper">
        <Head :title="customer.name" />
        <div class="border-bottom border-secondary-subtle mb-2">
            <div
                v-if="permissions.equipment.delete"
                id="manage-customer-dropdown"
                class="dropdown float-end"
            >
                <button
                    class="btn rounded-circle dropdown-toggle"
                    type="button"
                    data-bs-toggle="dropdown"
                >
                    <fa-icon icon="ellipsis-vertical" />
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <span
                            class="dropdown-item pointer"
                            @click="disableEquipment"
                        >
                            Disable Equipment
                        </span>
                    </li>
                </ul>
            </div>
            <CustomerDetails />
        </div>
        <div class="row justify-content-center">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">
                            <RefreshButton
                                :only="['equipment-data']"
                                class="float-start w-auto"
                            />
                            <h5 class="text-center">
                                {{ equipment.equip_name }}
                            </h5>
                        </div>
                        <CustomerEquipmentData
                            :equipment-data="equipmentData"
                        />
                    </div>
                </div>
            </div>
        </div>
        <div v-if="customer.site_count > 1" class="row my-4">
            <div class="col">
                <CustomerEquipmentSites :equipment="equipment" />
            </div>
        </div>
        <div class="row my-4">
            <div class="col">
                <CustomerNote :equipment="equipment" />
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import CustomerDetails from "@/Components/Customer/CustomerDetails.vue";
import CustomerEquipmentData from "@/Components/Customer/CustomerEquipmentData.vue";
import CustomerEquipmentSites from "@/Components/Customer/CustomerEquipmentSites.vue";
import CustomerNote from "@/Components/Customer/CustomerNote.vue";
import RefreshButton from "@/Components/_Base/Buttons/RefreshButton.vue";
import verifyModal from "@/Modules/verifyModal";
import { customer, permissions } from "@/State/CustomerState";
import { router } from "@inertiajs/vue3";

const props = defineProps<{
    equipment: customerEquipment;
    equipmentData: customerEquipmentData[];
}>();

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
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
