<template>
    <Head :title="breadcrumbs.at(-1)?.title" />
    <v-app id="app-layout-wrapper">
        <AppHeader @toggle-navbar="navbarActive = !navbarActive" />
        <AppSideNav :navbar-active="navbarActive" />
        <v-main class="bg-stone-200">
            <AppBreadcrumbs />
            <div class="p-4 mb-auto">
                <slot />
            </div>
            <AppFooter />
        </v-main>
    </v-app>
</template>

<script setup lang="ts">
import { computed, ref } from "vue";
import AppHeader from "./AppHeader.vue";
import AppSideNav from "./AppSideNav.vue";
import AppFooter from "./AppFooter.vue";
import AppBreadcrumbs from "./AppBreadcrumbs.vue";
import { usePage, router } from "@inertiajs/vue3";

/*
|---------------------------------------------------------------------------
| Dynamic Navbar Controls
|---------------------------------------------------------------------------
*/
const navbarActive = ref<boolean | null>(null);
router.on("navigate", () => (navbarActive.value = null));

/*
|---------------------------------------------------------------------------
| Breadcrumbs are imported to set the Title Element
|---------------------------------------------------------------------------
*/
const breadcrumbs = computed<breadcrumbs[]>(
    () => usePage<pageProps>().props.breadcrumbs
);
</script>
