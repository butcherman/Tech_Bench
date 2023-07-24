<template>
    <div id="app-layout">
        <AppHeader />
        <div class="container-fluid">
            <!-- <AppSideNav /> -->
            <div id="content" class="content">
                <div class="content-wrapper">
                    <AppBreadcrumbs />
                    <AppAlerts />
                    <StepNavigation
                        :step-list="stepList"
                        :current-step="stepId"
                    />
                    <div class="row justify-content-center">
                        <div class="col-md-10">
                            <div class="card">
                                <div class="card-body">
                                    <slot />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <AppFooter />
            </div>
        </div>
        <AppFlash />
    </div>
</template>

<script setup lang="ts">
import AppHeader from "./AppLayout/AppHeader.vue";
import AppBreadcrumbs from "./AppLayout/AppBreadcrumbs.vue";
import AppAlerts from "./AppLayout/AppAlerts.vue";
import AppFooter from "./AppLayout/AppFooter.vue";
import AppFlash from "./AppLayout/AppFlash.vue";
import StepNavigation from "@/Components/_Base/StepNavigation.vue";
import { ref, computed } from "vue";
import { usePage } from "@inertiajs/vue3";

import "../../scss/Layouts/appLayout.scss";

const page: pageData = usePage();
const stepId = computed<number>(() =>
    page.props.stepId ? page.props.stepId : 0
);

const stepList = ref([
    {
        id: 1,
        name: "Secure Admin Login",
        icon: "fa-house",
        active: stepId.value === 1 ? true : false,
    },
    {
        id: 2,
        name: "Basic Settings",
        icon: "fa-cog",
        active: stepId.value === 2 ? true : false,
    },
    {
        id: 3,
        name: "Email Settings",
        icon: "fa-envelope",
        active: stepId.value === 3 ? true : false,
    },
    {
        id: 4,
        name: "User Settings",
        icon: "fa-users-cog",
        active: stepId.value === 4 ? true : false,
    },
    {
        id: 5,
        name: "Finish",
        icon: "fa-check",
        active: stepId.value === 5 ? true : false,
    },
]);
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
    width: 100%;
    .content-wrapper {
        padding: 25px;
        margin-bottom: 75px;
    }
}
</style>
