<script setup lang="ts">
import VueForm from "@/core/forms/components/VueForm.vue";
import { object, string } from "yup";
import { update } from "@/wayfinder/routes/admin/user/user-settings";
import SwitchInput from "@/core/forms/components/validatedInputs/SwitchInput.vue";
import Collapse from "@/core/components/Collapse.vue";

defineEmits<{
    success: [];
}>();

const props = defineProps<{
    autoLogoutTimer: number;
    oath: OathConfig;
    roleList: UserRole[];
    twoFa: MultiFactorConfig;
}>();

const initValues = {
    auto_logout_timer: props.autoLogoutTimer,
    twoFa: props.twoFa,
    oath: props.oath,
};
const schema = object({});
</script>

<template>
    <VueForm
        name="security-settings-form"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="update.url()"
        submit-method="put"
        submit-text="Update User Settings"
        do-not-reset
        v-slot="{ values }"
        @success="$emit('success')"
    >
        <fieldset class="border mb-3 py-3">
            <legend>Two Factor Authentication</legend>
            <div class="ms-4 flex flex-col gap-3">
                <SwitchInput
                    name="twoFa.enabled"
                    label="Enable Two-Factor Authentication"
                />

                <Collapse
                    :show="values.twoFa.enabled"
                    class="flex flex-col gap-3"
                >
                    <SwitchInput
                        id="require-2fa"
                        name="twoFa.required"
                        label="Require Two-Factor Authentication"
                    />
                    <SwitchInput
                        id="save-device"
                        name="twoFa.allow_save_device"
                        label="Allow Users to Save Devices for Future Login"
                    />
                    <SwitchInput
                        id="allow-via-email"
                        name="twoFa.methods.email"
                        label="Allow Email as Two Factor Method"
                    />
                    <SwitchInput
                        id="allow-via-authenticator"
                        name="twoFa.methods.authenticator"
                        label="Allow Authenticator App as Two Factor Method"
                    />
                </Collapse>
            </div>
        </fieldset>
    </VueForm>
</template>
