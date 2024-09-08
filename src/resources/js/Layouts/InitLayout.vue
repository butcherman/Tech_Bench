<template>
    <div id="auth-layout-wrapper" class="container-fluid">
        <div class="row align-items-center h-100">
            <div class="col">
                <StepNavigation :step-list="stepList" :current-step="step" />
                <AppAlerts />
                <slot />
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppAlerts from "./AppLayout/AppAlerts.vue";
import StepNavigation from "@/Components/_Base/StepNavigation.vue";
import { ref, computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import "../../scss/Layouts/authLayout.scss";

const step = computed<number>(() => usePage<initProps>().props.step || 0);

const stepList = ref([
    {
        id: 1,
        name: "Secure Admin Login",
        icon: "fa-house",
    },
    {
        id: 2,
        name: "Basic Settings",
        icon: "fa-cog",
    },
    {
        id: 3,
        name: "Email Settings",
        icon: "fa-envelope",
    },
    {
        id: 4,
        name: "User Settings",
        icon: "fa-users-cog",
    },
    {
        id: 5,
        name: "Finish",
        icon: "fa-check",
    },
]);
</script>
