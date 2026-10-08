<script setup lang="ts">
import BaseButton from "@/core/components/buttons/BaseButton.vue";
import InitLayout from "@/layouts/InitLayout.vue";
import SubmitButton from "@/core/components/buttons/SubmitButton.vue";
import TechBenchConfigForm from "@/features/administration/forms/TechBenchConfigForm.vue";
import { onMounted } from "vue";
import { welcome } from "@/wayfinder/routes/init";
import { useSetupState } from "@/features/init/state/setupState";

const props = defineProps<{
    rules: string[];
    roles: UserRole[];
    user: User;
    hasPass: boolean;
}>();

const { markStepInProgress, onStepSuccess, showForm } = useSetupState();

onMounted(() => markStepInProgress(4));
</script>

<script lang="ts">
export default { layout: InitLayout };
</script>
<template>
    <div v-if="showForm" class="flex flex-col justify-center p-2">
        <h1 class="text-center">Administrator</h1>
        <p class="text-center">
            Lastly, lets make sure that the Administrator Account is secure.
        </p>
    </div>
</template>
