<template>
    <VueForm
        ref="backupSettingsForm"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="$route('maint.backups.settings.update')"
        submit-method="put"
        submit-text="Update Backup Settings"
    >
        <div class="d-flex justify-content-center">
            <div>
                <CheckboxSwitch
                    id="enable-auto"
                    name="nightly_backup"
                    label="Enable Nightly Backups"
                    help="When enabled, backups will run every night"
                />
                <CheckboxSwitch
                    id="enable-cleanup"
                    name="nightly_cleanup"
                    label="Enable Auto Backup Cleanup"
                    help="When enabled, backups will be kept based on the cleanup schedule noted below.  When disabled, all backups are kept regardless of age."
                />
                <CheckboxSwitch
                    id="encryption"
                    name="encryption"
                    label="Encrypt Backup"
                    help="When enabled, backups are password protected"
                />
            </div>
        </div>
        <TextInput
            id="password"
            name="password"
            label="Encryption Password"
            type="password"
            :disabled="!backupSettingsForm?.getFieldValue('encryption')"
        />
        <TextInput
            id="email"
            name="mail_to"
            label="Email Notifications To"
            type="email"
            help="Any notifications generated by the backup process will be sent to this email address"
        />
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import CheckboxSwitch from "../_Base/CheckboxSwitch.vue";
import { ref } from "vue";
import { boolean, object, string } from "yup";

const props = defineProps<{
    nightly_backup: boolean;
    nightly_cleanup: boolean;
    encryption: boolean;
    password: string | null;
    mail_to: string;
}>();

const backupSettingsForm = ref<InstanceType<typeof VueForm> | null>(null);

const initValues = {
    nightly_backup: props.nightly_backup,
    nightly_cleanup: props.nightly_cleanup,
    encryption: props.encryption,
    password: props.password,
    mail_to: props.mail_to,
};
const schema = object({
    nightly_backup: boolean().required(),
    nightly_cleanup: boolean().required(),
    encryption: boolean().required(),
    password: string().when("encryption", {
        is: true,
        then: (schema) => schema.required(),
        otherwise: (schema) => schema.nullable(),
    }),
    mail_to: string().email().required(),
});
</script>
