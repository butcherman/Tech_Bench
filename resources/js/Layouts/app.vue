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
\                <AppFooter />
            </div>
        </div>
        <AppFlash />
        <NotificationAlert />
    </div>
</template>

<script setup lang="ts">
import AppHeader from "./AppLayout/AppHeader.vue";
import AppSideNav from "./AppLayout/AppSideNav.vue";
import AppFooter from "./AppLayout/AppFooter.vue";
import AppBreadcrumbs from "./AppLayout/AppBreadcrumbs.vue";
import AppAlerts from "./AppLayout/AppAlerts.vue";
import AppFlash from "./AppLayout/AppFlash.vue";
import NotificationAlert from "./AppLayout/NotificationAlert.vue";
import { onMounted } from 'vue';
import { router, usePage } from "@inertiajs/vue3";
import { closeNavbar } from "@/State/LayoutState";
import { setNotifications, triggerFetchInterval } from "@/State/NotificationState";

router.on("navigate", () => closeNavbar);

/**
 * Handle pushing notifications to their proper place on initial mount
 * All notifications received after mount will be pushed via Ajax call
 */
onMounted(() => {
    const page:pageData = usePage();
    setNotifications(page.props.notifications);
    triggerFetchInterval();
})
</script>
