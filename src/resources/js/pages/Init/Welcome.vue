<script setup lang="ts">
import BaseButton from "@/core/components/buttons/BaseButton.vue";
import InitLayout from "@/layouts/InitLayout.vue";
import { useSetupState } from "@/features/init/state/setupState";

const { advanceStep } = useSetupState();
</script>

<script lang="ts">
export default { layout: InitLayout };
</script>
<template>
    <div>
        <h1 class="text-center font-bold text-2xl">
            Welcome to the Tech Bench
        </h1>
        <p class="text-center m-5">Looks like this is your first time here.</p>
        <p class="text-center m-5">
            Before you can start using the Tech Bench, we need to do some basic
            setup first.
        </p>
        <p class="text-center">
            <BaseButton @click="advanceStep"> Setup Tech Bench </BaseButton>
        </p>
    </div>
</template>
