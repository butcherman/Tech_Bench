<script setup lang="ts">
import BooleanBadge from "@/core/components/badges/BooleanBadge.vue";
import EmailTestForm from "../forms/EmailTestForm.vue";
import Modal from "@/core/components/Modal.vue";

const props = defineProps<{
    testValues: EmailSettings;
}>();

const showTestModal = defineModel();
</script>

<template>
    <Modal v-model="showTestModal" title="Send Test Email" size="sm">
        <div class="flex flex-col gap-5">
            <p>SMTP Configuration</p>
            <div>
                <h5>SMTP Host</h5>
                <p class="px-5">{{ testValues.host }}</p>
            </div>
            <div class="flex gap-5">
                <div>
                    <h5>Port</h5>
                    <p class="px-5">{{ testValues.port }}</p>
                </div>
                <div>
                    <h5>Encryption</h5>
                    <p class="px-5">{{ testValues.encryption }}</p>
                </div>
                <div>
                    <h5>Use Authentication</h5>
                    <p class="px-5">
                        <BooleanBadge :value="testValues.require_auth" />
                    </p>
                </div>
            </div>
            <EmailTestForm :test-values @success="showTestModal = false" />
        </div>
    </Modal>
</template>
