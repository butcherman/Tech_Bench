<template>
    <VueForm
        ref="form"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="submitRoute"
        :submit-text="submitText"
        submit-method="put"
        @success="$emit('success')"
    >
        <TextInput
            id="password-expires"
            name="expire"
            label="Password Expires in Days (enter 0 for no expiration)"
        />
        <fieldset>
            <legend>Password Complexity</legend>
            <RangeInput
                id="min-length"
                name="min_length"
                label="Password Minimum Length"
                :min="3"
                :max="25"
            />
            <div class="m-3">
                <p>
                    A password should contain at least one each of the
                    following:
                </p>
                <CheckboxSwitch
                    id="policy-uppercase"
                    name="contains_uppercase"
                    label="Uppercase Letter"
                />
                <CheckboxSwitch
                    id="policy-lowercase"
                    name="contains_lowercase"
                    label="Lowercase Letter"
                />
                <CheckboxSwitch
                    id="policy-number"
                    name="contains_number"
                    label="Number (0-9)"
                />
                <CheckboxSwitch
                    id="policy-special"
                    name="contains_special"
                    label="Special Character (!@#$%^&*)"
                />
                <CheckboxSwitch
                    id="policy-compromised"
                    name="disable_compromised"
                    label="Disable Known Compromised Passwords (Example: Pa$$word!)"
                />
            </div>
        </fieldset>
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import RangeInput from "@/Forms/_Base/RangeInput.vue";
import CheckboxSwitch from "@/Forms/_Base/CheckboxSwitch.vue";
import { object, boolean, number } from "yup";
import { computed } from "vue";

defineEmits(["success"]);
const props = defineProps<{
    policy: passwordPolicy;
    init?: boolean;
}>();

const submitRoute = computed(() =>
    props.init
        ? route("init.step-3.submit")
        : route("admin.user.password-policy.update")
);

const submitText = computed(() =>
    props.init ? "Save and Continue" : "Update Password Policy"
);

const initValues = {
    expire: props.policy.expire,
    min_length: props.policy.min_length,
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
