<script setup lang="ts">
import FlashAlert from "../_Shared/FlashAlert.vue";
</script>

<template>
    <div id="auth-layout-wrapper">
        <FlashAlert />
        <slot />
    </div>
</template>

<style>
#auth-layout-wrapper {
    background: linear-gradient(135deg, #1f0683 0%, #24308e 50%, #0b77ca 100%);
    min-height: 100vh;
    width: 100%;
}
</style>
