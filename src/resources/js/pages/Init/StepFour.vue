<script setup lang="ts">
import InitAdminUserForm from "@/features/init/forms/InitAdminUserForm.vue";
import InitLayout from "@/layouts/InitLayout.vue";
import { onMounted } from "vue";
import { useSetupState } from "@/features/init/state/setupState";

const props = defineProps<{
    rules: string[];
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
        <InitAdminUserForm :user @success="onStepSuccess" />
    </div>
</template>
