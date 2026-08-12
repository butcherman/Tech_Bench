<script setup lang="ts">
import FlashAlert from "./components/FlashAlert.vue";
</script>

<template>
    <div id="layout-wrapper">
        <flash-alert />
        <slot />
    </div>
</template>

<style>
#layout-wrapper {
    background: linear-gradient(135deg, #1f0683 0%, #24308e 30%, #0b77ca 100%);
    min-height: 100vh;
    width: 100%;
}
</style>
