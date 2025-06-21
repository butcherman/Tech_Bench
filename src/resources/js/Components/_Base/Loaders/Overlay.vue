<script setup lang="ts">
import Loading from "vue3-loading-overlay";
import AtomLoader from "./AtomLoader.vue";

import "vue3-loading-overlay/dist/vue3-loading-overlay.css";

defineProps<{
    loading: boolean;
    fullPage?: boolean;
}>();
</script>

<template>
    <div class="vld-parent">
        <Loading :active="loading" :is-full-page="fullPage">
            <slot name="loader">
                <AtomLoader />
            </slot>
        </Loading>
        <slot />
    </div>
</template>

<style scoped>
.vld-overlay.is-active {
    background-color: rgba(197, 192, 192, 0.5);
}
</style>
