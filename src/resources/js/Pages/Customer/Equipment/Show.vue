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
                            <AlertButton
                                v-if="changeAlert.equipment"
                                class="float-start"
                                title="Files Updated.  Refresh for most recent data"
                            />
                            <RefreshButton
                                :only="['equipment-data']"
                                class="float-start w-auto"
                                @loading-start="toggleLoading('equipment')"
                                @loading-complete="clearAlert('equipment')"
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
        <div class="row my-4">
            <div class="col">
                <CustomerFile :equipment="equipment" />
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
import CustomerFile from "@/Components/Customer/CustomerFile.vue";
import RefreshButton from "@/Components/_Base/Buttons/RefreshButton.vue";
import AlertButton from "@/Components/_Base/Buttons/AlertButton.vue";
import verifyModal from "@/Modules/verifyModal";
import { onMounted, onUnmounted } from "vue";
import {
    customer,
    permissions,
    loading,
    toggleLoading,
    changeAlert,
    clearAlert,
} from "@/State/CustomerState";
import { router } from "@inertiajs/vue3";
import { registerEquipmentChannel } from "@/Modules/CustomerBroadcasting.module";

const props = defineProps<{
    equipment: customerEquipment;
    equipmentData: customerEquipmentData[];
}>();

/**
 * Register to Customer Channel
 */
const channelName = props.equipment.cust_equip_id;
onMounted(() => {
    registerEquipmentChannel(channelName);
});

/**
 * Leave Customer Channel
 */
onUnmounted(() => Echo.leave(`customer.${channelName}`));

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
