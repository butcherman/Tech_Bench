<script setup lang="ts">
import InitLayout from "@/layouts/InitLayout.vue";
import InitUserSecurityForm from "@/features/init/forms/InitUserSecurityForm.vue";
import { onMounted } from "vue";
import { useSetupState } from "@/features/init/state/setupState";

const { markStepInProgress, onStepSuccess } = useSetupState();

defineProps<{
    policy: PasswordPolicy;
}>();

onMounted(() => markStepInProgress(3));
</script>

<script lang="ts">
export default { layout: InitLayout };
</script>
<template>
    <div class="flex flex-col gap-4 justify-center items-center h-full">
        <h1 class="text-center">Security</h1>
        <p class="text-center">
            Now we will setup User Security Settings. Adjust the password policy
            and MFA settings as you see fit.
        </p>
        <InitUserSecurityForm :policy @success="onStepSuccess" />
    </div>
</template>
