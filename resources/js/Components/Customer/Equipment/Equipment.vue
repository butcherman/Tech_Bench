<template>
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <RefreshButton
                    :only="['equipment']"
                    @start="toggleEquipLoad"
                    @end="toggleEquipLoad"
                />
                Equipment:
                <NewEquipment v-if="permissions?.equipment.create" />
            </div>
            <Overlay :loading="equipLoad">
                <ShowEquipment />
            </Overlay>
        </div>
    </div>
</template>

<script setup lang="ts">
import Overlay from "@/Components/Base/Overlay.vue";
import RefreshButton from "@/Components/Base/Buttons/RefreshButton.vue";
import NewEquipment from "@/Components/Customer/Equipment/NewEquipment.vue";
import ShowEquipment from "@/Components/Customer/Equipment/ShowEquipment.vue";
import {
    permissions,
    toggleEquipLoad,
    equipLoad,
} from "@/State/Customer/CustomerState";
</script>
