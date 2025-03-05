<script setup lang="ts">
import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import FlashAlert from "../_Shared/FlashAlert.vue";
import AppHeader from "./AppHeader.vue";

const appTitle = computed<string | undefined>(
    () => usePage<pageProps>().props.breadcrumbs.at(-1)?.title
);
</script>

<template>
    <div id="app-layout-wrapper">
        <Head :title="appTitle" />
        <FlashAlert />
        <AppHeader />
        <section id="app-content">
            <slot />
        </section>
    </div>
</template>

<style>
#app-layout-wrapper {
    @apply h-screen z-0 flex flex-col;
}

#app-content {
    @apply md:ms-64 mt-14 z-0;
    @apply bg-gray-200;
    @apply flex flex-col grow;
}
</style>
