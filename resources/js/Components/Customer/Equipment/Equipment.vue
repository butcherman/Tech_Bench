<template>
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <RefreshButton
                    :only="['equipment']"
                    @start="toggleLoad"
                    @end="toggleLoad"
                />
                Equipment:
                <NewEquipment
                    v-if="permission?.equipment.create"
                    :existing-equipment="equipment"
                />
            </div>
            <Overlay :loading="loading">
                <ShowEquipment :equipment="equipment" />
            </Overlay>
        </div>
    </div>
</template>

<script setup lang="ts">
import Overlay from "@/Components/Base/Overlay.vue";
import RefreshButton from "@/Components/Base/Buttons/RefreshButton.vue";
import NewEquipment from "@/Components/Customer/Equipment/NewEquipment.vue";
import ShowEquipment from "@/Components/Customer/Equipment/ShowEquipment.vue";
import { ref, provide, inject } from "vue";
import { router } from "@inertiajs/vue3";
import {
    custPermissionsKey,
    toggleEquipLoadKey,
} from "@/SymbolKeys/CustomerKeys";
import type { customerEquipmentType, customerPermissionType } from "@/Types";

defineProps<{
    equipment: customerEquipmentType[];
}>();

const permission = inject(custPermissionsKey) as customerPermissionType;

/**
 * Loading State of Component
 */
const loading = ref<boolean>(false);
const toggleLoad = () => {
    loading.value = !loading.value;
};
provide(toggleEquipLoadKey, toggleLoad);
</script>
