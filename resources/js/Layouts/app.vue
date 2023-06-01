<template>
    <div class="app-layout">
        <AppHeader />
        <div class="container-fluid">
            <AppSideNav />
            <div id="content" class="content">
                <div class="content-wrapper">
                    <AppBreadcrumbs />
                    <AppAlerts />
                    <slot />
                </div>
                <AppFooter />
            </div>
        </div>
        <AppFlash />
        <!-- <div class="toast-container position-fixed bottom-0 end-0 p-3">
            <template v-for="msg in newNotifications">
                <NotificationToast />
            </template>
        </div> -->
    </div>
</template>

<script setup lang="ts">
import AppHeader from "./AppLayout/AppHeader.vue";
import AppSideNav from "./AppLayout/AppSideNav.vue";
import AppFooter from "./AppLayout/AppFooter.vue";
import AppBreadcrumbs from "./AppLayout/AppBreadcrumbs.vue";
import AppAlerts from "./AppLayout/AppAlerts.vue";
import AppFlash from "./AppLayout/AppFlash.vue";
import { router } from "@inertiajs/vue3";
import { closeNavbar, pushAlert } from "@/State/LayoutState";

router.on("navigate", () => closeNavbar);
</script>
