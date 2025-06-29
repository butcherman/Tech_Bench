<script setup lang="ts">
import SwitchInput from "@/Forms/_Base/SwitchInput.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import VueForm from "@/Forms/_Base/VueForm.vue";
import { ref } from "vue";
import { boolean, object, string } from "yup";

const props = defineProps<{
    nightly_backup: boolean;
    nightly_cleanup: boolean;
    encryption: boolean;
    password: string | null;
}>();

const needsPassword = ref(props.encryption);

/*
|-------------------------------------------------------------------------------
| Vee Validate
|-------------------------------------------------------------------------------
*/
const initValues = {
    nightly_backup: props.nightly_backup,
    nightly_cleanup: props.nightly_cleanup,
    encryption: props.encryption,
    password: props.password,
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
});
</script>

<template>
    <VueForm
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="$route('maint.backups.settings.update')"
        submit-method="put"
        submit-text="Update Backup Settings"
    >
        <div class="flex justify-center">
            <div>
                <SwitchInput
                    id="enable-auto"
                    name="nightly_backup"
                    label="Enable Nightly Backups"
                    help="When enabled, backups will run every night"
                />
                <SwitchInput
                    id="enable-cleanup"
                    name="nightly_cleanup"
                    label="Enable Auto Backup Cleanup"
                    help="When enabled, backups will be kept based on the cleanup schedule noted below.  When disabled, all backups are kept regardless of age."
                />
                <SwitchInput
                    id="encryption"
                    name="encryption"
                    label="Encrypt Backup"
                    help="When enabled, backups are password protected"
                    @change="needsPassword = !needsPassword"
                />
            </div>
        </div>
        <TextInput
            id="password"
            name="password"
            label="Encryption Password"
            type="password"
            :disabled="!needsPassword"
        />
    </VueForm>
</template>
