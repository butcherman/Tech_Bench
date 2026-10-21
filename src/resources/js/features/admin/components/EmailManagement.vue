<script setup lang="ts">
import Card from "@/core/components/Card.vue";
import EmailSettingsForm from "../forms/EmailSettingsForm.vue";
import EmailTestModal from "./EmailTestModal.vue";
import { nextTick, ref } from "vue";

defineProps<{
    settings: EmailSettings;
}>();

const showTestModal = ref(false);
const testSettings = ref<EmailSettings>();

const onSendTestEmail = (values: EmailSettings): void => {
    testSettings.value = values;
    nextTick(() => (showTestModal.value = true));
};
</script>

<template>
    <div class="flex justify-center">
        <Card size="md">
            <EmailSettingsForm :settings @send-test="onSendTestEmail" />
        </Card>
        <div v-if="testSettings">
            <EmailTestModal
                v-model="showTestModal"
                :test-values="testSettings"
            />
        </div>
    </div>
</template>
