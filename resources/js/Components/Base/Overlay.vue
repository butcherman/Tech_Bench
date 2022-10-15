<template>
    <div class="vld-parent">
        <Loading :active="loading" :is-full-page="false" />
        <slot />
    </div>
</template>

<script setup lang="ts">
    import Loading from 'vue3-loading-overlay';

    defineProps<{
        loading: boolean;
    }>();
</script>

<style scoped lang="scss">
    @import 'vue3-loading-overlay/dist/vue3-loading-overlay.css';
</style>
