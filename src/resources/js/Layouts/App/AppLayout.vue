<template>
    <div id="app-layout-wrapper">
        <Head :title="appTitle" />
        <AppFlash />
        <AppHeader
            id="app-header"
            @toggle-navbar="navbarHidden = !navbarHidden"
        />
        <AppSideNav id="app-navbar" :navbar-hidden="navbarHidden" />
        <section id="app-content">
            <div id="app-page-wrapper">
                <AppBreadcrumbs class="mb-2" />
                <AppAlerts />
                <slot />
            </div>
            <AppFooter />
        </section>
    </div>
</template>

<script setup lang="ts">
import AppAlerts from "./AppAlerts.vue";
import AppBreadcrumbs from "./AppBreadcrumbs.vue";
import AppFlash from "./AppFlash.vue";
import AppFooter from "./AppFooter.vue";
import AppHeader from "./AppHeader.vue";
import AppSideNav from "./AppSideNav.vue";
import { router, usePage } from "@inertiajs/vue3";
import { computed, ref } from "vue";

/*
|-------------------------------------------------------------------------------
| Dynamic Navbar Controls
|-------------------------------------------------------------------------------
*/
const navbarHidden = ref<boolean | null>(true);
router.on("navigate", () => (navbarHidden.value = true));

/*
|-------------------------------------------------------------------------------
| Breadcrumbs are imported to set the Title Element
|-------------------------------------------------------------------------------
*/
const breadcrumbs = computed<breadcrumbs[]>(
    () => usePage<pageProps>().props.breadcrumbs
);
const appTitle = computed<string | undefined>(
    () => breadcrumbs.value.at(-1)?.title
);
</script>
