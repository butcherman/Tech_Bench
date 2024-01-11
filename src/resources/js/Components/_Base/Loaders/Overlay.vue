<template>
    <div class="vld-parent">
        <Loading :active="loading" :is-full-page="false">
            <slot name="loader">
                <AtomLoader />
            </slot>
        </Loading>
        <slot />
    </div>
</template>

<script setup lang="ts">
import Loading from "vue3-loading-overlay";
import AtomLoader from "./AtomLoader.vue";

import "vue3-loading-overlay/dist/vue3-loading-overlay.css";

defineProps<{
    loading: boolean;
}>();
</script>

<style scoped lang="scss">
.vld-overlay.is-active {
    background-color: rgba(197, 192, 192, 0.5);
}
</style>
