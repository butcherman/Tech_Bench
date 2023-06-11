<template>
    <VueForm
        ref="userAccountForm"
        :initial-values="initValues"
        :validation-schema="validation"
        submit-text="Update Account Settings"
        @submit="onSubmit"
    >
        <TextInput id="first-name" name="first_name" label="First Name" />
        <TextInput id="last-name" name="last_name" label="Last Name" />
        <TextInput id="email" name="email" label="Email Address" />
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import { object, string } from "yup";
import { ref } from 'vue';
import { useForm } from "@inertiajs/vue3";

const props = defineProps<{
    user: user;
}>();

const userAccountForm = ref<InstanceType<typeof VueForm> | null>(null);

const initValues = {
    first_name: props.user.first_name,
    last_name: props.user.last_name,
    email: props.user.email,
};
const validation = object({
    first_name: string().required(),
    last_name: string().required(),
    email: string().email().required(),
});

const onSubmit = (form: user) => {
    console.log(form);
    console.log(props.user.username);

    const formData = useForm(form);
    formData.post(route('user.settings.set', props.user.username), {
        onFinish: () => userAccountForm.value?.endSubmit(),
        onSuccess: () => {
            userAccountForm.value?.resetForm();
            //  Update the form with the new values
            userAccountForm.value?.setFieldValue('first_name', props.user.first_name);
            userAccountForm.value?.setFieldValue('last_name', props.user.last_name);
            userAccountForm.value?.setFieldValue('email', props.user.email);
        },
    });
};
</script>
