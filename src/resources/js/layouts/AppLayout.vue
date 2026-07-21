<script setup lang="ts">
import AppFooter from "./components/AppFooter.vue";
import AppHeader from "./components/AppHeader.vue";
import AppSideNav from "./components/AppSideNav.vue";
import Breadcumbs from "./components/Breadcumbs.vue";
import FlashAlert from "./components/FlashAlert.vue";
import StaticAlert from "./components/StaticAlert.vue";
import { Head } from "@inertiajs/vue3";
import { ref, useTemplateRef } from "vue";

const appHeader = useTemplateRef("app-header");

/**
 * Navbar control
 */
const navbarHidden = ref<boolean>(true);
const toggleNav = () => (navbarHidden.value = !navbarHidden.value);
const onClickOutsideHandler = [
    () => (navbarHidden.value = true),
    {
        ignore: [appHeader],
    },
];
</script>

<template>
    <div class="h-screen flex flex-col">
        <FlashAlert />
        <Head title="page title" />
        <AppHeader ref="app-header" @toggle-navbar="toggleNav" />
        <AppSideNav
            v-model="navbarHidden"
            v-on-click-outside="onClickOutsideHandler"
        />
        <section class="mt-14 lg:ms-64 grow bg-gray-200 flex flex-col">
            <div class="p-5 grow flex flex-col">
                <Breadcumbs class="mb-2" />
                <StaticAlert />
                <div class="grow">
                    <slot />
                </div>
            </div>
            <AppFooter />
        </section>
    </div>
</template>
