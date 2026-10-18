<script setup lang="ts">
import AppHeader from "./components/AppHeader.vue";
import AppSideNav from "./components/AppSideNav.vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import Breadcrumbs from "./components/Breadcrumbs.vue";

const pageTitle = computed<string | undefined>(
    () =>
        usePage().props.breadcrumbs.find((crumb) => crumb.is_current_page)
            ?.title,
);

const sideNavHidden = ref<boolean>(true);
const toggleNav = (): boolean => (sideNavHidden.value = !sideNavHidden.value);
const closeNav = () => (sideNavHidden.value = true);

/**
 * When navigating, close the Navbar on mobile
 */
router.on("before", () => {
    sideNavHidden.value = true;
});
</script>

<template>
    <div class="h-screen flex flex-col">
        <Head :title="pageTitle" />
        <AppHeader @toggle-navbar="toggleNav" />
        <AppSideNav v-model="sideNavHidden" v-on-click-outside="closeNav" />
        <section
            class="mt-14 lg:ms-64 flex flex-col gap-5 p-5 bg-content h-full"
        >
            <Breadcrumbs />
            <div>
                <slot />
            </div>
        </section>
    </div>
</template>
