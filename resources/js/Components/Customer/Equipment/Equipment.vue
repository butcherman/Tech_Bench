<template>
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <button
                    class="btn btn-sm"
                    title="Refresh Equipment"
                    v-tooltip
                    @click="refreshEquipment"
                >
                    <fa-icon icon="fa-rotate" :spin="loading" />
                </button>
                Equipment:
                <NewEquipment v-if="permission?.equipment.create" :existing-equipment="equipment" />
            </div>
            <Overlay :loading="loading">
                <ShowEquipment :equipment="equipment" />
            </Overlay>
        </div>
    </div>
</template>

<script setup lang="ts">
import Overlay from "@/Components/Base/Overlay.vue";
import NewEquipment from "@/Components/Customer/Equipment/NewEquipment.vue";
import ShowEquipment from "@/Components/Customer/Equipment/ShowEquipment.vue";
import { ref, provide, inject } from "vue";
import { router } from "@inertiajs/vue3";
import { custPermissionsKey, toggleEquipLoadKey } from "@/SymbolKeys/CustomerKeys";
import type { customerEquipmentType, customerPermissionType } from "@/Types";

defineProps<{
    equipment: customerEquipmentType[];
}>();

const permission = inject(custPermissionsKey) as customerPermissionType;
const loading = ref<boolean>(false);
const toggleLoad = () => { loading.value = !loading.value }
provide(toggleEquipLoadKey, toggleLoad);

const refreshEquipment = () => {
    toggleLoad();
    router.reload({
        only: ["flash", "equipment"],
        preserveScroll: true,
        onFinish: () => toggleLoad(),
    });
};
</script>
