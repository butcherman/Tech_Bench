<script setup lang="ts">
import InitLayout from "@/layouts/InitLayout.vue";
import { useSetupState } from "@/features/init/state/setupState";

const { stepList, markStepComplete } = useSetupState();

const props = defineProps<{}>();

console.log(stepList.value);
</script>

<script lang="ts">
export default { layout: InitLayout };
</script>
<template>
    <div class="flex justify-center">
        <h1>Step 4</h1>
    </div>
</template>
