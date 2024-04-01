<template>
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <RefreshButton
                    :only="['files']"
                    @loading-start="toggleLoading('files')"
                    @loading-complete="toggleLoading('files')"
                />
                Files:
                <CustomerFileCreate class="float-end" :equipment="equipment" />
            </div>
            <Overlay :loading="loading.files">
                <div v-if="!files.length">
                    <h6 class="text-center">No Files</h6>
                </div>
                <div v-for="file in files">
                    {{ file }}
                </div>
            </Overlay>
        </div>
    </div>
</template>

<script setup lang="ts">
import RefreshButton from "../_Base/Buttons/RefreshButton.vue";
import Overlay from "../_Base/Loaders/Overlay.vue";
import CustomerFileCreate from "@/Components/Customer/CustomerFileCreate.vue";
import { loading, toggleLoading, files } from "@/State/CustomerState";

defineProps<{
    equipment?: customerEquipment;
}>();
</script>
