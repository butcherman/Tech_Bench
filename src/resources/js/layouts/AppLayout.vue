<script setup lang="ts">
import AppFooter from "./components/AppFooter.vue";
import AppHeader from "./components/AppHeader.vue";
import AppSideNav from "./components/AppSideNav.vue";
import Breadcumbs from "./components/Breadcumbs.vue";
import FlashAlert from "./components/FlashAlert.vue";
import StaticAlert from "./components/StaticAlert.vue";
import { Head } from "@inertiajs/vue3";
import { ref } from "vue";

const navbarHidden = ref<boolean>(true);
const toggleNav = () => (navbarHidden.value = !navbarHidden.value);
</script>

<template>
    <div class="h-screen flex flex-col">
        <FlashAlert />
        <Head title="page title" />
        <AppHeader @toggle-navbar="toggleNav" />
        <AppSideNav :navbar-hidden="navbarHidden" />
        <section class="mt-14 lg:ms-64 grow bg-gray-200 flex flex-col">
            <div class="p-5 grow">
                <Breadcumbs class="mb-2" />
                <StaticAlert />
                <div>
                    <slot />
                </div>
            </div>
            <AppFooter />
        </section>
    </div>
</template>
