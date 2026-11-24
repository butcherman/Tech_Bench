<script setup lang="ts">
import BaseButton from "@/core/components/buttons/BaseButton.vue";
import EmailSettingsForm from "@/features/admin/forms/EmailSettingsForm.vue";
import EmailTestModal from "@/features/admin/components/EmailTestModal.vue";
import InitLayout from "@/layouts/InitLayout.vue";
import SubmitButton from "@/core/components/buttons/SubmitButton.vue";
import { onMounted } from "vue";
import { step1 } from "@/wayfinder/routes/init";
import { useSetupState } from "@/features/init/state/setupState";
import { nextTick, ref } from "vue";

const { markStepInProgress, onStepSuccess, showForm } = useSetupState();

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

const showTestModal = ref(false);
const testSettings = ref<EmailSettings>();

const onSendTestEmail = (values: EmailSettings): void => {
    testSettings.value = values;
    nextTick(() => (showTestModal.value = true));
};
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
        <EmailSettingsForm
            :settings
            init
            @send-test="onSendTestEmail"
            @success="onStepSuccess"
        >
            <template #submit-button>
                <div class="flex justify-center gap-3">
                    <BaseButton
                        :href="step1.url()"
                        class="basis-1/3"
                        text="Back"
                        variant="primary"
                    />
                    <SubmitButton class="basis-1/3" text="Next" />
                </div>
            </template>
        </EmailSettingsForm>
        <div v-if="testSettings">
            <EmailTestModal
                v-model="showTestModal"
                :test-values="testSettings"
            />
        </div>
    </div>
</template>
