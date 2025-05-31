<script setup lang="ts">
import AppBreadcrumbs from "./AppBreadcrumbs.vue";
import AppFooter from "./AppFooter.vue";
import AppHeader from "./AppHeader.vue";
import AppNotificationToast from "./AppNotificationToast.vue";
import AppSideNav from "./AppSideNav.vue";
import FlashAlert from "../_Shared/FlashAlert.vue";
import StaticAlert from "../_Shared/StaticAlert.vue";
import { computed, onMounted, ref } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import { useBroadcastStore } from "@/Stores/BroadcastStore";

/*
|-------------------------------------------------------------------------------
| Navbar Controls
|-------------------------------------------------------------------------------
*/
const navbarHidden = ref<boolean>(true);

/*
|-------------------------------------------------------------------------------
| Dynamically set Page Title based on breadcrumbs.
|-------------------------------------------------------------------------------
*/
const appTitle = computed<string | undefined>(
    () =>
        usePage<pageProps>().props.breadcrumbs.find(
            (crumb) => crumb.is_current_page == true
        )?.title
);

/*
|-------------------------------------------------------------------------------
| Register to Users Notification Channel
|-------------------------------------------------------------------------------
*/
const broadcast = useBroadcastStore();
onMounted(() => broadcast.registerNotificationChannel());
router.on("before", (e) => {
    e.detail.visit.headers["X-Socket-ID"] = Echo.socketId() ?? "";
    navbarHidden.value = true;
});
</script>

<template>
    <div class="h-screen flex flex-col">
        <FlashAlert />
        <Head :title="appTitle" />
        <AppNotificationToast />
        <AppHeader @toggle-navbar="navbarHidden = !navbarHidden" />
        <AppSideNav :navbar-hidden="navbarHidden" />
        <section class="mt-14 lg:ms-64 grow bg-gray-200 flex flex-col">
            <div class="p-5 grow">
                <AppBreadcrumbs class="mb-2" />
                <StaticAlert />
                <div>
                    <slot />
                </div>
            </div>
            <AppFooter />
        </section>
    </div>
</template>
