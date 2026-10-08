<script setup lang="ts">
import BaseButton from "@/core/components/buttons/BaseButton.vue";
import InitLayout from "@/layouts/InitLayout.vue";
import { onMounted } from "vue";
import { step1 } from "@/wayfinder/routes/init/index.js";
import { useSetupState } from "@/features/init/state/setupState";

const { markStepComplete, markStepInProgress } = useSetupState();

const props = defineProps<{}>();

onMounted(() => markStepInProgress(0));
</script>

<script lang="ts">
export default { layout: InitLayout };
</script>
<template>
    <div class="flex flex-col gap-4 justify-center items-center h-full">
        <h1 class="text-center">Welcome to the Tech Bench</h1>
        <p class="text-center p-2">
            Let's get your Tech Bench installation configured. This will take
            just a few minutes.
        </p>
        <BaseButton
            :href="step1.url()"
            class="w-3/4"
            text="Get Started"
            @click="markStepComplete(0)"
        />
        <!-- <BaseButton class="w-3/4" text="Recover From Backup" /> -->
    </div>
</template>
