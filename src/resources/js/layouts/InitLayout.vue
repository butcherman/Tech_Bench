<script setup lang="ts">
import Card from "@/core/components/Card.vue";
import FlashAlert from "./components/FlashAlert.vue";
import SetupProgress from "@/features/init/components/SetupProgress.vue";
</script>

<template>
    <div id="layout-wrapper">
        <FlashAlert />
        <div class="h-screen w-screen flex justify-center items-center">
            <div class="w-full lg:w-2/3 m-4">
                <Card>
                    <div class="flex flex-col md:flex-row">
                        <div
                            class="grow border-e border-b md:border-b-0 border-slate-300 p-2"
                        >
                            <h1 class="text-center">Tech Bench</h1>
                            <h3 class="text-center">Setup</h3>
                            <div class="my-4 border-b border-slate-300" />
                            <div class="p-4">
                                <SetupProgress />
                            </div>
                        </div>
                        <div class="basis-2/3">
                            <slot />
                        </div>
                    </div>
                </Card>
            </div>
        </div>
    </div>
</template>

<style>
#layout-wrapper {
    background: linear-gradient(135deg, #1f0683 0%, #24308e 30%, #0b77ca 100%);
    min-height: 100vh;
    height: 100vh;
    width: 100%;
}
</style>
