<template>
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <RefreshButton :only="['files']" />
                Files:
                <NewFile />
            </div>
            <Overlay :loading="loading">
                <h3 class="text-center">Hello World!!!</h3>
            </Overlay>
        </div>
    </div>
</template>

<script setup lang="ts">
import Overlay from "@/Components/Base/Overlay.vue";
import RefreshButton from "@/Components/Base/Buttons/RefreshButton.vue";
import NewFile from '@/Components/Customer/Files/NewFile.vue';
import AddButton from "@/Components/Base/Buttons/AddButton.vue";
import { ref, reactive, onMounted, inject, provide } from "vue";
import {
    custPermissionsKey,
    toggleFilesLoadKey,
} from "@/SymbolKeys/CustomerKeys";
import type { customerPermissionType } from "@/Types";

const props = defineProps<{
    files: [];
}>();

const permission = inject(custPermissionsKey) as customerPermissionType;

/**
 * Loading State of Component
 */
const loading = ref<boolean>(false);
const toggleLoad = () => {
    loading.value = !loading.value;
};
provide(toggleFilesLoadKey, toggleLoad);
</script>
