<template>
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <CustomerEquipmentCreate
                    v-if="permissions.equipment.create"
                    class="float-end"
                />
                <AlertButton v-if="changeAlert.equipment" />
                <RefreshButton
                    :only="['equipmentList']"
                    @loading-start="toggleLoading('equipment')"
                    @loading-complete="clearAlert('equipment')"
                />
                Equipment:
            </div>
            <Overlay :loading="loading.equipment" class="h-100">
                <h6 v-if="!equipmentList.length" class="text-center">
                    No Equipment
                </h6>
                <ul class="list-group">
                    <li
                        v-for="equip in equipmentList"
                        :key="equip.cust_equip_id"
                        class="list-group-item"
                    >
                        <div class="card">
                            <div class="card-body p-0 m-0">
                                <Link
                                    as="button"
                                    :href="
                                        $route('customers.equipment.show', [
                                            customer.slug,
                                            equip.cust_equip_id,
                                        ])
                                    "
                                    class="btn w-100 btn-info"
                                >
                                    {{ equip.equip_name }}
                                </Link>
                            </div>
                        </div>
                    </li>
                </ul>
            </Overlay>
        </div>
    </div>
</template>

<script setup lang="ts">
import Overlay from "../_Base/Loaders/Overlay.vue";
import RefreshButton from "../_Base/Buttons/RefreshButton.vue";
import AlertButton from "../_Base/Buttons/AlertButton.vue";
import CustomerEquipmentCreate from "./CustomerEquipmentCreate.vue";
import {
    loading,
    toggleLoading,
    equipmentList,
    customer,
    permissions,
    changeAlert,
    clearAlert,
} from "@/State/CustomerState";
</script>
