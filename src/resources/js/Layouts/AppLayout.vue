<template>
    <div id="app-layout-wrapper" class="container-fluid">
        <AppHeader @navbar-toggle="navbarActive = !navbarActive" />
        <main id="app-wrapper">
            <AppSideNav :active="navbarActive" />
            <div id="content-wrapper">
                <AppBreadcrumbs />
                <AppAlerts />
                <div id="content">
                    <slot />
                </div>
            </div>
            <AppFooter />
        </main>
    </div>
</template>

<script setup lang="ts">
import AppHeader from "@/Layouts/AppLayout/AppHeader.vue";
import AppSideNav from "@/Layouts/AppLayout/AppSideNav.vue";
import AppFooter from "./AppLayout/AppFooter.vue";
import AppBreadcrumbs from "./AppLayout/AppBreadcrumbs.vue";
import AppAlerts from "./AppLayout/AppAlerts.vue";
import { ref } from "vue";
import { router } from "@inertiajs/vue3";
import "../../scss/Layouts/appLayout.scss";

/**
 * Navbar shown state
 */
const navbarActive = ref(false);
/**
 * Close the navbar when routing to new page
 */
router.on("navigate", () => (navbarActive.value = false));
</script>
