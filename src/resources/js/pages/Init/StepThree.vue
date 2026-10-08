<script setup lang="ts">
import BaseButton from "@/core/components/buttons/BaseButton.vue";
import InitLayout from "@/layouts/InitLayout.vue";
import PasswordPolicyForm from "@/features/auth/forms/PasswordPolicyForm.vue";
import SubmitButton from "@/core/components/buttons/SubmitButton.vue";
import { onMounted } from "vue";
import { useSetupState } from "@/features/init/state/setupState";
import { step2 } from "@/wayfinder/routes/init";

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
            as you see fit.
        </p>
        <PasswordPolicyForm :policy init @success="onStepSuccess">
            <template #submit-button>
                <div class="flex justify-center gap-3">
                    <BaseButton
                        :href="step2.url()"
                        class="basis-1/3"
                        text="Back"
                    />
                    <SubmitButton class="basis-1/3" text="Next" />
                </div>
            </template>
        </PasswordPolicyForm>
    </div>
</template>
