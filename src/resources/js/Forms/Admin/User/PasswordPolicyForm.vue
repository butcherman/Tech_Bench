<template>
    <VueForm
        submit-method="put"
        :initial-values="initValues"
        :submit-route="submitRoute"
        :submit-text="submitText"
        :validation-schema="schema"
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
                <SwitchInput
                    id="policy-uppercase"
                    name="contains_uppercase"
                    label="Uppercase Letter"
                />
                <SwitchInput
                    id="policy-lowercase"
                    name="contains_lowercase"
                    label="Lowercase Letter"
                />
                <SwitchInput
                    id="policy-number"
                    name="contains_number"
                    label="Number (0-9)"
                />
                <SwitchInput
                    id="policy-special"
                    name="contains_special"
                    label="Special Character (!@#$%^&*)"
                />
                <SwitchInput
                    id="policy-compromised"
                    name="disable_compromised"
                    label="Disable Known Compromised Passwords (Example: Pa$$word!)"
                />
            </div>
        </fieldset>
    </VueForm>
</template>

<script setup lang="ts">
import RangeInput from "@/Forms/_Base/RangeInput.vue";
import SwitchInput from "@/Forms/_Base/SwitchInput.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import VueForm from "@/Forms/_Base/VueForm.vue";
import { object, number, boolean } from "yup";
import { computed } from "vue";

defineEmits(["success"]);
const props = defineProps<{
    policy: passwordPolicy;
    init?: boolean;
}>();

/*
|-------------------------------------------------------------------------------
| Handle Form
|-------------------------------------------------------------------------------
*/
const submitRoute = computed(() =>
    props.init
        ? route("init.step-3.submit")
        : route("admin.user.password-policy.update")
);

const submitText = computed(() =>
    props.init ? "Save and Continue" : "Update Password Policy"
);

/*
|-------------------------------------------------------------------------------
| Validation
|-------------------------------------------------------------------------------
*/
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
