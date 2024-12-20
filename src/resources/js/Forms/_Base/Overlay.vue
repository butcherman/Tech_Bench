<template>
    <div class="vld-parent">
        <LoadingVue :active="loading" :is-full-page="false">
            <slot name="loader">
                <TrinityRingsLoader />
            </slot>
        </LoadingVue>
        <slot />
    </div>
</template>

<script setup lang="ts">
import LoadingVue from "vue3-loading-overlay";
import TrinityRingsLoader from "@/Components/_Base/Loaders/TrinityRingsLoader.vue";

// Overlay Styling
import "vue3-loading-overlay/dist/vue3-loading-overlay.css";

defineProps<{
    loading: boolean;
}>();
</script>

<style scoped>
.vld-overlay.is-active {
    background-color: rgba(197, 192, 192, 0.5);
}
</style>
