<script setup lang="ts">
import BaseButton from "@/core/components/buttons/BaseButton.vue";
import RangeSliderInput from "@/core/forms/components/validatedInputs/RangeSliderInput.vue";
import SubmitButton from "@/core/components/buttons/SubmitButton.vue";
import SwitchInput from "@/core/forms/components/validatedInputs/SwitchInput.vue";
import TextInput from "@/core/forms/components/validatedInputs/TextInput.vue";
import VueForm from "@/core/forms/components/VueForm.vue";
import { object, number, boolean } from "yup";
import { submit } from "@/wayfinder/routes/init/step-3";
import { step2 } from "@/wayfinder/routes/init";

defineEmits<{
    success: [];
}>();

const props = defineProps<{
    policy: PasswordPolicy & {
        twoFa: MultiFactorConfig;
    };
}>();

const initValues = {
    expire: String(props.policy.expire),
    min_length: String(props.policy.min_length),
    contains_uppercase: props.policy.contains_uppercase,
    contains_lowercase: props.policy.contains_lowercase,
    contains_number: props.policy.contains_number,
    contains_special: props.policy.contains_special,
    disable_compromised: props.policy.disable_compromised,
    twoFa: {
        enabled: props.policy.twoFa.enabled ?? true,
        required: props.policy.twoFa.required ?? false,
        allow_save_device: props.policy.twoFa.allow_save_device ?? true,
        methods: {
            email: props.policy.twoFa.allow_via_email ?? true,
            authenticator: props.policy.twoFa.allow_via_authenticator ?? true,
        },
    },
};

const schema = object({
    expire: number().required(),
    min_length: number().required(),
    contains_uppercase: boolean().required(),
    contains_lowercase: boolean().required(),
    contains_number: boolean().required(),
    contains_special: boolean().required(),
    disable_compromised: boolean().required(),
    twoFa: object({
        required: boolean().required(),
        allow_save_device: boolean().required(),
        methods: object({
            email: boolean().required(),
            authenticator: boolean().required(),
        }),
    }),
});
</script>

<template>
    <VueForm
        name="password-policy-form"
        submit-method="put"
        submit-text="Update Password Policy"
        :initial-values="initValues"
        :submit-route="submit.url()"
        :validation-schema="schema"
        do-not-reset
        @success="$emit('success')"
    >
        <TextInput
            id="password-expires"
            name="expire"
            label="Password Expires in Days (enter 0 for no expiration)"
        />
        <fieldset>
            <legend>Password Complexity</legend>
            <RangeSliderInput
                name="min_length"
                label="Password Minimum Length"
                value-text="Length"
                :min="3"
                :max="25"
            />
            <div class="m-3 flex flex-col gap-3">
                <p>
                    A password should contain at least one each of the
                    following:
                </p>
                <SwitchInput
                    name="contains_uppercase"
                    label="Uppercase Letter"
                />
                <SwitchInput
                    name="contains_lowercase"
                    label="Lowercase Letter"
                />
                <SwitchInput name="contains_number" label="Number (0-9)" />
                <SwitchInput
                    name="contains_special"
                    label="Special Character (!@#$%^&*)"
                />
                <SwitchInput
                    name="disable_compromised"
                    label="Disable Known Compromised Passwords (Example: Pa$$word!)"
                />
            </div>
        </fieldset>
        <fieldset class="border mb-3 py-3">
            <legend>Two Factor Authentication</legend>
            <div class="ms-4 flex flex-col gap-3">
                <SwitchInput
                    name="twoFa.enabled"
                    label="Enable Two-Factor Authentication"
                />

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
            </div>
        </fieldset>

        <template #submit-button>
            <div class="flex justify-center gap-3">
                <BaseButton :href="step2.url()" class="basis-1/3" text="Back" />
                <SubmitButton class="basis-1/3" text="Next" />
            </div>
        </template>
    </VueForm>
</template>
