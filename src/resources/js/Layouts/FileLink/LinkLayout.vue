<script setup lang="ts">
import AppFooter from "../App/AppFooter.vue";
import FlashAlert from "../_Shared/FlashAlert.vue";
import LinkHeader from "./LinkHeader.vue";
</script>

<template>
    <div id="link-layout-wrapper" class="flex flex-col bg-gray-200">
        <LinkHeader />
        <FlashAlert />
        <section id="link-content" class="mt-14 p-3 grow">
            <slot />
        </section>
        <AppFooter />
    </div>
</template>

<style scoped lang="postcss">
#link-layout-wrapper {
    min-height: 100vh;
    width: 100%;
}
</style>
