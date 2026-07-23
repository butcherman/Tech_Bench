<script setup lang="ts">
import Card from "@/core/components/Card.vue";
import FlashAlert from "./components/FlashAlert.vue";
import SetupStepNavigation from "@/features/init/components/SetupStepNavigation.vue";
</script>

<template>
    <div id="auth-layout-wrapper" class="flex flex-col items-center">
        <FlashAlert />
        <div
            class="flex flex-col gap-4 justify-center items-center h-screen w-11/12"
        >
            <div class="w-full">
                <div class="w-full">
                    <SetupStepNavigation />
                </div>
                <div class="flex w-full justify-center">
                    <Card>
                        <slot />
                    </Card>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
#auth-layout-wrapper {
    background: linear-gradient(135deg, #1f0683 0%, #24308e 30%, #0b77ca 100%);
    min-height: 100vh;
    width: 100%;
}
</style>
