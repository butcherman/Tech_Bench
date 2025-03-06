<script setup lang="ts">
import { router, usePage } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import FlashAlert from "../_Shared/FlashAlert.vue";
import AppHeader from "./AppHeader.vue";
import AppNotificationToast from "./AppNotificationToast.vue";
import AppSideNav from "./AppSideNav.vue";
import AppBreadcrumbs from "./AppBreadcrumbs.vue";

/**
 * Navbar Controls
 */
const navbarHidden = ref<boolean>(true);
router.on("navigate", () => (navbarHidden.value = true));

/**
 * Dynamically set Page Title based on breadcrumbs.
 */
const appTitle = computed<string | undefined>(
    () =>
        usePage<pageProps>().props.breadcrumbs.find(
            (crumb) => crumb.is_current_page == true
        )?.title
);
</script>

<template>
    <div id="app-layout-wrapper" class="h-screen z-0 flex flex-col">
        <Head :title="appTitle" />
        <FlashAlert />
        <AppNotificationToast />
        <AppHeader @toggle-navbar="navbarHidden = !navbarHidden" />
        <AppSideNav :navbar-hidden="navbarHidden" />
        <section
            id="app-content"
            class="md:ms-64 mt-14 z-0 bg-gray-200 flex flex-col grow"
        >
            <div id="app-page-wrapper" class="grow p-5">
                <AppBreadcrumbs class="mb-2" />
                <slot />
            </div>
        </section>
    </div>
</template>
