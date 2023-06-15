<template>
    <VueForm
        ref="initUserForm"
        :initial-values="initValues"
        :validation-schema="schema"
        submit-text="Create Password"
        @submit="onSubmit"
    >
        <TextInput
            id="password"
            name="password"
            label="Password"
            type="password"
        />
        <TextInput
            id="password-confirmation"
            name="password_confirmation"
            label="Confirm Password"
            type="password"
        />
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import { object, string } from "yup";
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";

type passwordForm = {
    username: string;
    password: string;
    password_confirmation: string;
}

const props = defineProps<{
    token: string;
    user: user;
}>();

const initUserForm = ref<InstanceType<typeof VueForm> | null>(null);

const initValues = {
    username: props.user.username,
    password: "",
    password_confirmation: "",
};
const schema = object({
    username: string().required(),
    password: string().required(),
    password_confirmation: string()
        .required("Enter Password Again")
        .test("confirmed", "Passwords do not match", (value) => {
            return value === initUserForm.value?.getFieldValue("password");
        }),
});

const onSubmit = (form: passwordForm) => {
    console.log(form);

    const formData = useForm(form);
    formData.post(route('initialize.submit', props.token), {
        onFinish: () => initUserForm.value?.endSubmit(),
    });
};
</script>
