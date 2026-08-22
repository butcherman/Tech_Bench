<script setup lang="ts">
import RangeSliderInput from "@/core/forms/components/validatedInputs/RangeSliderInput.vue";
import SwitchInput from "@/core/forms/components/validatedInputs/SwitchInput.vue";
import TextInput from "@/core/forms/components/validatedInputs/TextInput.vue";
import VueForm from "@/core/forms/components/VueForm.vue";
import { object, number, boolean } from "yup";
import { computed } from "vue";
import { submit } from "@/wayfinder/routes/init/step-3";
import { update } from "@/wayfinder/routes/admin/user/password-policy";

defineEmits<{
    success: [];
}>();

const props = defineProps<{
    policy: PasswordPolicy;
    init?: boolean;
}>();

const submitRoute = computed(() => (props.init ? submit.url() : update.url()));

const submitText = computed(() =>
    props.init ? "Save and Continue" : "Update Password Policy",
);

const initValues = {
    expire: String(props.policy.expire),
    min_length: String(props.policy.min_length),
    contains_uppercase: props.policy.contains_uppercase,
    contains_lowercase: props.policy.contains_lowercase,
    contains_number: props.policy.contains_number,
    contains_special: props.policy.contains_special,
    disable_compromised: props.policy.disable_compromised,
};

const schema = object({
    expire: number().required(),
    min_length: number().required(),
    contains_uppercase: boolean().required(),
    contains_lowercase: boolean().required(),
    contains_number: boolean().required(),
    contains_special: boolean().required(),
    disable_compromised: boolean().required(),
});
</script>

<template>
    <VueForm
        name="password-policy-form"
        submit-method="put"
        :initial-values="initValues"
        :submit-route="submitRoute"
        :submit-text="submitText"
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
    </VueForm>
</template>
