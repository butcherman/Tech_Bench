<template>
    <Head title="Forgot Password" />
    <AuthLayout>
        <h6 class="text-center">
            Enter your email address for instructions on accessing your account.
        </h6>
        <form @submit="onSubmit" novalidate>
            <TextInput id="email-address" type="email" label="Email Address" name="email" />
            <SubmitButton :submitted="isSubmitting" />
        </form>
    </AuthLayout>
</template>

<script setup lang="ts">
    import AuthLayout                 from '@/Layouts/authLayout.vue';
    import TextInput                  from '@/Components/Base/Input/TextInput.vue';
    import SubmitButton               from '@/Components/Base/Input/SubmitButton.vue';
    import { ref }                    from 'vue';
    import { useForm }                from '@inertiajs/inertia-vue3';
    import { useForm as useVeeForm }  from 'vee-validate';
    import * as yup                   from 'yup';

    const isSubmitting     = ref(false);
    const { handleSubmit } = useVeeForm({
        validationSchema: {
            email: yup.string().email().required('You must enter an email address'),
        }
    });

    const onSubmit = handleSubmit(form => {
        isSubmitting.value = true;
        const loginForm    = useForm(form);

        loginForm.post(route('password.submit-email'), {
            onFinish: () => isSubmitting.value = false,
        });
    });
</script>
