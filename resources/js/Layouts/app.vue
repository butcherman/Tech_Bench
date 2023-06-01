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
        <!-- <div class="toast-container position-fixed bottom-0 end-0 p-3">
            <template v-for="msg in newNotifications">
                <NotificationToast />
            </template>
        </div> -->
        <div
            class="toast-container position-absolute top-0 start-50 translate-middle p-3"
        >
            <template v-for="msg in flashMessage">
                <AlertToast :background="msg.type" :message="msg.message" />
            </template>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppHeader from "./AppLayout/AppHeader.vue";
import AppSideNav from "./AppLayout/AppSideNav.vue";
import AppFooter from "./AppLayout/AppFooter.vue";
import AppBreadcrumbs from "./AppLayout/AppBreadcrumbs.vue";
import AppAlerts from "./AppLayout/AppAlerts.vue";
import { onMounted, watch, computed, ref } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import { closeNavbar } from "@/State/LayoutState";

router.on("navigate", () => closeNavbar);

const page: pageData = usePage();
const flash = computed(() => page.props.flash);
const flashMessage = ref<flashMessage[]>([]);

watch(flash, () => checkFlashMessages());
onMounted(() => {
    checkFlashMessages();
    // setNotifications(notificationList.value);
    // triggerFetchInterval();
});

const checkFlashMessages = () => {
    for (const [type, message] of Object.entries(flash.value)) {
        if (message !== null) {
            flashMessage.value.push({
                type,
                message,
            });
        }
    }
};
</script>
