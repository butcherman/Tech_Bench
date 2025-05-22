<script setup lang="ts">
import FlashAlert from "../_Shared/FlashAlert.vue";
import StepNavigation from "@/Components/_Base/StepNavigation.vue";
import { computed, ref } from "vue";
import { usePage } from "@inertiajs/vue3";

type initProps = {
    step: number;
} & pageProps;

const step = computed<number>(() => usePage<initProps>().props.step || 0);

// TODO - Add Link to navigate between steps
const stepList = ref([
    {
        id: 1,
        name: "Basic Settings",
        icon: "fa-cog",
    },
    {
        id: 2,
        name: "Email Settings",
        icon: "fa-envelope",
    },
    {
        id: 3,
        name: "User Settings",
        icon: "fa-users-cog",
    },
    {
        id: 4,
        name: "Secure Admin Login",
        icon: "fa-house",
    },
    {
        id: 5,
        name: "Verify Information",
        icon: "fa-certificate",
    },
    {
        id: 6,
        name: "Finish",
        icon: "fa-check",
    },
]);
</script>

<template>
    <div id="init-layout-wrapper">
        <FlashAlert />
        <div class="flex flex-col items-center justify-center h-screen">
            <StepNavigation :step-list="stepList" :current-step="step" />
            <slot />
        </div>
    </div>
</template>

<style scoped>
#init-layout-wrapper {
    background: linear-gradient(135deg, #1f0683 0%, #24308e 50%, #0b77ca 100%);
    min-height: 100vh;
    width: 100%;
}
</style>
