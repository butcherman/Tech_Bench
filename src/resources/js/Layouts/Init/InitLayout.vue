<template>
    <div
        id="init-layout-wrapper"
        class="h-screen align-content-center justify-items-center"
    >
        <Head title="First Time Setup" />
        <Card class="w-full md:w-3/4 md:min-h-5/6 md:h-5/6 overflow-y-auto">
            <div class="h-full flex flex-col overflow-auto">
                <StepNavigation
                    :step-list="stepList"
                    :current-step="step"
                    class="overflow-auto flex-none"
                />
                <hr class="my-3" />
                <div class="grow justify-items-center align-content-center">
                    <slot />
                </div>
            </div>
        </Card>
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
#init-layout-wrapper {
    background: linear-gradient(135deg, #1f0683 0%, #24308e 50%, #0b77ca 100%);
    min-height: 100vh;
    width: 100%;
}
</style>
