<template>
    <VueForm
        ref="passwordForm"
        :initial-values="initValues"
        :validation-schema="schema"
        submit-text="Update Password"
        @submit="onSubmit"
    >
        <TextInput id="current-password" name="current_password" label="Current Password" type="password" v-focus />
        <TextInput id="password" name="password" label="New Password" type="password" />
        <TextInput id="password-confirmed" name="password_confirmation" label="Confirm Password" type="password" />
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from '@/Forms/_Base/VueForm.vue';
import TextInput from '@/Forms/_Base/TextInput.vue';
import { ref } from "vue";
import { object, string } from 'yup';
import { useForm } from '@inertiajs/vue3';

type passwordForm = {
    current_password: string;
    password: string;
    password_confirmation: string;
}

const passwordForm = ref<InstanceType<typeof VueForm> | null>(null);
const initValues = {
    current_password: '',
    password: '',
    password_confirmation: '',
}
const schema = object({
    current_password: string().required('Enter Current Password'),
    password: string().required(),
    password_confirmation: string().required('Enter Password Again').test('confirmed', 'Passwords do not match', (value) => {
        return value === passwordForm.value?.getFieldValue('password');
    }),
});

const onSubmit = (form: passwordForm) => {
    console.log(form);

    const formData = useForm(form);
    formData.put(route('user-password.update'), {
        onFinish: () => passwordForm.value?.endSubmit(),
        onSuccess: () => passwordForm.value?.resetForm(),
    });



    //  user-password.update
}
</script>
