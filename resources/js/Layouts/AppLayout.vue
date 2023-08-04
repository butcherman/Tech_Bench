<template>
    <div id="app-layout">
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
        <NotificationAlert />
        <NotificationBase />
    </div>
</template>

<script setup lang="ts">
import AppHeader from "./AppLayout/AppHeader.vue";
import AppSideNav from "./AppLayout/AppSideNav.vue";
import AppBreadcrumbs from "./AppLayout/AppBreadcrumbs.vue";
import AppAlerts from "./AppLayout/AppAlerts.vue";
import AppFooter from "./AppLayout/AppFooter.vue";
import AppFlash from "./AppLayout/AppFlash.vue";
import NotificationAlert from "./AppLayout/NotificationAlert.vue";
import NotificationBase from "@/Components/Notifications/NotificationBase.vue";
import { onMounted, onUnmounted } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import { closeNavbar } from "@/State/LayoutState";
import {
    newNotificationCount,
    notificationList,
    registerNotificationChannel,
    leaveNotificationChannel,
} from "@/State/NotificationState";

import "../../scss/Layouts/appLayout.scss";

router.on("navigate", () => {
    closeNavbar();
});

/**
 * Handle pushing notifications to their proper place on initial page load
 * All notifications received after mount will be pushed via Broadcast Channel
 */
onMounted(() => {
    const page: pageData = usePage();

    if(page.props.app.user) {
        registerNotificationChannel(page.props.app.user.username);
        newNotificationCount.value = page.props.notifications.new;
        notificationList.value = page.props.notifications.list;
    }
});

onUnmounted(() => {
    leaveNotificationChannel();
})
</script>

<style lang="scss">
@import "../../scss/Layouts/appLayout.scss";

.content {
    background-color: $content-background;
    position: absolute;
    top: $header-height;
    z-index: 1;
    padding: 0;
    margin: 0;
    min-height: calc(100vh - #{$header-height});
    @media (min-width: $brk-lg) {
        left: $navbar-width;
        width: calc(100% - #{$navbar-width});
    }
    @media (max-width: $brk-lg) {
        left: 0;
        width: 100%;
    }
    .content-wrapper {
        padding: 25px;
        margin-bottom: 75px;
    }
}
</style>
