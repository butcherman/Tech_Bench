<script setup lang="ts">
import BaseButton from "@/core/components/buttons/BaseButton.vue";
import EmailConfigForm from "@/features/administration/forms/EmailConfigForm.vue";
import InitLayout from "@/layouts/InitLayout.vue";
import SubmitButton from "@/core/components/buttons/SubmitButton.vue";
import { onMounted } from "vue";
import { step1 } from "@/wayfinder/routes/init";
import { useSetupState } from "@/features/init/state/setupState";

const { markStepInProgress, onStepSuccess, showForm } = useSetupState();

// TODO - Add test email

defineProps<{
    settings: {
        from_address: string;
        host: string;
        port: number;
        encryption: string;
        require_auth: boolean;
        username: string;
        password: string;
    };
}>();

onMounted(() => markStepInProgress(2));
</script>

<script lang="ts">
export default { layout: InitLayout };
</script>
<template>
    <div v-if="showForm" class="flex flex-col justify-center p-2">
        <h1 class="text-center">Email Settings</h1>
        <p class="text-center">
            The Tech Bench requires email settings in order to send notification
            emails. Please enter the proper email server information below.
        </p>
        <EmailConfigForm :settings init @success="onStepSuccess">
            <template #submit-button>
                <div class="flex justify-center gap-3">
                    <BaseButton
                        :href="step1.url()"
                        class="basis-1/3"
                        text="Back"
                    />
                    <SubmitButton class="basis-1/3" text="Next" />
                </div>
            </template>
        </EmailConfigForm>
    </div>
</template>
