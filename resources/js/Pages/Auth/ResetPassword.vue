<template>
    <Head title="Reset Password" />
    <AuthLayout>
        <h6 class="text-center">Reset Password</h6>
        <VueForm ref="resetForm"
            :validation-schema="formData.validationSchema"
            :initial-values="formData.initialValues"
            @submit="onSubmit"
        >
            <TextInput
                id="email"
                label="Email Address"
                name="email"
            />
            <TextInput
                id="password"
                type="password"
                label="New Password"
                name="password"
            />
            <TextInput
                id="password-confirmation"
                type="password"
                label="Confirm Password"
                name="password_confirmation"
            />
        </VueForm>
    </AuthLayout>
</template>

<script setup lang="ts">
    import AuthLayout  from '@/Layouts/authLayout.vue';
    import VueForm     from '@/Components/Base/VueForm.vue';
    import TextInput   from '@/Components/Base/Input/TextInput.vue';
    import { ref }     from 'vue';
    import { useForm } from '@inertiajs/inertia-vue3';
    import * as yup    from 'yup';

    const props = defineProps<{
        email : string;
        token : string;
    }>();

    const resetForm = ref<InstanceType<typeof VueForm> | null>(null);
    const formData = {
        validationSchema: {
            email                : yup.string().email().required('You must enter an email address'),
            password             : yup.string().required('You must enter a password'),
            password_confirmation: yup.string().required('Enter password again')
        },
        initialValues: {
            token: props.token,
            email: props.email,
        }
    }

    interface resetFormType {
        email                : string;
        password             : string;
        password_confirmation: string;
    }

    const onSubmit = (form:resetFormType) => {
        const myForm = useForm(form);

        myForm.post(route('password.reset-submit'), {
            onFinish: () => resetForm.value?.endSubmit,
        });
    };
</script>
