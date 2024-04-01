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
                <CustomerFileCreate class="float-end" />
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
import { ref, reactive, onMounted } from "vue";
import { customer, loading, toggleLoading, files } from "@/State/CustomerState";

// const props = defineProps<{}>();
</script>
