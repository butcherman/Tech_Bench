<template>
    <div class="flex flex-col h-screen">
        <Head title="First Time Setup" />
        <StepNavigation :step-list="stepList" :current-step="step" />
        <div class="flex grow place-items-center">
            <Card class="tb-card mx-auto">
                <div>
                    <slot />
                </div>
            </Card>
        </div>
    </div>
</template>

<script setup lang="ts">
import Card from "@/Components/_Base/Card.vue";
import StepNavigation from "@/Components/_Base/StepNavigation.vue";
import { ref, computed } from "vue";
import { usePage } from "@inertiajs/vue3";

const step = computed<number>(() => usePage<initProps>().props.step || 0);

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

<style>
body {
    background: linear-gradient(135deg, #1f0683 0%, #24308e 50%, #0b77ca 100%);
    /* min-height: 100vh;
    width: 100%; */
}
</style>
