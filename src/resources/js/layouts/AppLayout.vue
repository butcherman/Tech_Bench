<script setup lang="ts">
import AppFooter from "./components/AppFooter.vue";
import AppHeader from "./components/AppHeader.vue";
import AppSideNav from "./components/AppSideNav.vue";
import FlashAlert from "./components/FlashAlert.vue";
import ToastAlert from "./components/ToastAlert.vue";
import { Head } from "@inertiajs/vue3";
import { ref } from "vue";

/*
|-------------------------------------------------------------------------------
| Side Nav Control
|-------------------------------------------------------------------------------
*/
const sideNavHidden = ref<boolean>(true);
const toggleNav = () => (sideNavHidden.value = !sideNavHidden.value);
</script>

<template>
    <div class="h-screen flex flex-col">
        <Head title="Typography" />
        <FlashAlert />
        <ToastAlert />
        <AppHeader @toggle-navbar="toggleNav" />
        <AppSideNav v-model:open="sideNavHidden" />
        <section class="mt-14 lg:ms-64 grow bg-gray-200 flex flex-col">
            <div class="p-5 grow flex flex-col">
                <slot />
            </div>
            <AppFooter />
        </section>
    </div>
</template>
