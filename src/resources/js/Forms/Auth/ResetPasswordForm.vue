<template>
    <VueForm
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="submitRoute"
        :submit-method="submitMethod"
        :submit-text="submitText"
    >
        <TextInput id="email" name="email" placeholder="Email Address" />
        <TextInput
            id="password"
            name="password"
            type="password"
            placeholder="New Password"
            focus
        />
        <TextInput
            id="password-confirmation"
            name="password_confirmation"
            type="password"
            placeholder="Confirm Password"
        />
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import { computed } from "vue";
import { ref as reference, object, string } from "yup";

const props = defineProps<{
    email: string;
    token: string;
    initialize?: boolean;
}>();

const submitRoute = computed(() =>
    props.initialize
        ? route("initialize.update", props.token)
        : route("password.update")
);
const submitMethod = computed(() => (props.initialize ? "put" : "post"));
const submitText = computed(() =>
    props.initialize ? "Finish Setup" : "Reset Password"
);

const initValues = {
    email: props.email,
    token: props.token,
    password: null,
    password_confirmation: null,
};
const schema = object({
    email: string().email().required(),
    token: string().required(),
    password: string().required(),
    password_confirmation: string().oneOf(
        [reference("password")],
        "Passwords must match"
    ),
});
</script>
