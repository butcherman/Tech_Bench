<script setup lang="ts">
import AppFooter from "./components/AppFooter.vue";
import AppHeader from "./components/AppHeader.vue";
import AppSideNav from "./components/AppSideNav.vue";
import Breadcrumbs from "./components/Breadcrumbs.vue";
import FlashAlert from "./components/FlashAlert.vue";
import ToastAlert from "./components/ToastAlert.vue";
import UnbaggedErrors from "./components/UnbaggedErrors.vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import { computed, ref, useTemplateRef } from "vue";

const appHeader = useTemplateRef("app-header");

/*
|-------------------------------------------------------------------------------
| Page Title
|-------------------------------------------------------------------------------
*/
const pageTitle = computed<string | undefined>(
    () =>
        usePage().props.breadcrumbs.find((crumb) => crumb.is_current_page)
            ?.title,
);

/*
|-------------------------------------------------------------------------------
| Side Nav Control
|-------------------------------------------------------------------------------
*/
const sideNavHidden = ref<boolean>(true);
const toggleNav = (): boolean => (sideNavHidden.value = !sideNavHidden.value);

/**
 * When navigating, close the Navbar on mobile
 */
router.on("before", (ev) => {
    sideNavHidden.value = true;
});

/**
 * Close the navbar on mobile when tapped outside of it
 */
const onClickOutsideHandler = [
    () => (sideNavHidden.value = true),
    {
        ignore: [appHeader],
    },
];
</script>

<template>
    <div class="h-screen flex flex-col">
        <Head :title="pageTitle" />
        <FlashAlert />
        <ToastAlert />
        <AppHeader ref="app-header" @toggle-navbar="toggleNav" />
        <AppSideNav
            v-model:open="sideNavHidden"
            v-on-click-outside="onClickOutsideHandler"
        />
        <section class="mt-14 lg:ms-64 grow bg-gray-200 flex flex-col">
            <div class="p-5 grow flex flex-col">
                <Breadcrumbs class="mb-2" />
                <UnbaggedErrors class="mb-2" />
                <slot />
            </div>
            <AppFooter />
        </section>
    </div>
</template>
