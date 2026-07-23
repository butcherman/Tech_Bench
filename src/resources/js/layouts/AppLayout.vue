<script setup lang="ts">
import AppFooter from "./components/AppFooter.vue";
import AppHeader from "./components/AppHeader.vue";
import AppSideNav from "./components/AppSideNav.vue";
import Breadcumbs from "./components/Breadcumbs.vue";
import FlashAlert from "./components/FlashAlert.vue";
import StaticAlert from "./components/StaticAlert.vue";
import { Head, usePage } from "@inertiajs/vue3";
import { computed, ref, useTemplateRef } from "vue";

const appHeader = useTemplateRef("app-header");

/**
 * Set the Page Title to match Breadcrumbs title
 */
const pageTitle = computed<string | undefined>(
    () =>
        usePage().props.breadcrumbs.find((crumb) => crumb.is_current_page)
            ?.title,
);

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
        <Head :title="pageTitle" />
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
