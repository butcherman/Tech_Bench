<script setup lang="ts">
import Collapse from "@/core/components/Collapse.vue";
import PasswordInput from "@/core/forms/components/validatedInputs/PasswordInput.vue";
import SwitchInput from "@/core/forms/components/validatedInputs/SwitchInput.vue";
import VueForm from "@/core/forms/components/VueForm.vue";
import { object, string, boolean } from "yup";
import { update } from "@/wayfinder/routes/maint/backups";

defineEmits<{
    success: [];
}>();

const props = defineProps<{
    settings: BackupSettings;
}>();

const initValues = props.settings;
const schema = object({
    nightly_backup: boolean().required(),
    nightly_cleanup: boolean().required(),
    encryption: boolean().required(),
    password: string().when("encryption", {
        is: true,
        then: (schema) => schema.required(),
        otherwise: (schema) => schema.nullable(),
    }),
});
</script>

<template>
    <VueForm
        name="backup-settings-form"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="update.url()"
        submit-method="put"
        submit-text="Update Backup Settings"
        v-slot="{ values }"
        @success="$emit('success')"
    >
        <div class="flex justify-center">
            <div class="flex flex-col gap-3">
                <SwitchInput
                    name="nightly_backup"
                    label="Enable Nightly Backups"
                    help="When enabled, backups will run every night"
                />
                <SwitchInput
                    name="nightly_cleanup"
                    label="Enable Auto Backup Cleanup"
                    help="When enabled, backups will be kept based on the cleanup schedule noted below.  When disabled, all backups are kept regardless of age."
                />
                <SwitchInput
                    name="encryption"
                    label="Encrypt Backup"
                    help="When enabled, backups are password protected"
                />
            </div>
        </div>
        <Collapse :show="values.encryption">
            <PasswordInput
                name="password"
                label="Encryption Password"
                type="password"
            />
        </Collapse>
    </VueForm>
</template>
