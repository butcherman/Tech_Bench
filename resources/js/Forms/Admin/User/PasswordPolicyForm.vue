<template>
    <VueForm
        ref="passwordPolicyForm"
        :initial-values="initValues"
        :validation-schema="schema"
        submit-text="Update Password Policy"
        @submit="onSubmit"
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
            </div>
        </fieldset>
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import CheckboxSwitch from "@/Forms/_Base/CheckboxSwitch.vue";
import RangeInput from "@/Forms/_Base/RangeInput.vue";
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import { boolean, number, object } from "yup";

const props = defineProps<{
    policy: passwordPolicy;
}>();

const passwordPolicyForm = ref<InstanceType<typeof VueForm> | null>(null);
const initValues = {
    expire: props.policy.expire,
    min_length: props.policy.min_length,
    contains_uppercase: props.policy.contains_uppercase,
    contains_lowercase: props.policy.contains_lowercase,
    contains_number: props.policy.contains_number,
    contains_special: props.policy.contains_special,
};
const schema = object({
    expire: number().required(),
    min_length: number().required(),
    contains_uppercase: boolean().required(),
    contains_lowercase: boolean().required(),
    contains_number: boolean().required(),
    contains_special: boolean().required(),
});

const onSubmit = (form: passwordPolicy) => {
    const formData = useForm(form);

    formData.post(route("admin.users.password-policy.set"), {
        onFinish: () => passwordPolicyForm.value?.endSubmit(),
    });
};
</script>
