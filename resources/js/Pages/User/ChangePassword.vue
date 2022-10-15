<template>
    <Head title="Change Password" />
    <App>
        <div class="row justify-content-center">
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Please Enter New Password</h5>
                        <form @submit="onSubmit" novalidate>
                            <TextInput
                                id="current-password"
                                name="current_password"
                                label="Current Password"
                                type="password"
                            />
                            <TextInput
                                id="password"
                                name="password"
                                label="New Password"
                                type="password"
                            />
                            <TextInput
                                id="password-confirmation"
                                name="password_confirmation"
                                label="Confirm Password"
                                type="password"
                            />
                            <SubmitButton :submitted="isSubmitting" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </App>
</template>

<script setup lang="ts">
    import App                        from '@/Layouts/app.vue';
    import TextInput                  from '@/Components/Base/Input/TextInput.vue';
    import SubmitButton               from '@/Components/Base/Input/SubmitButton.vue';
    import { ref }                    from 'vue';
    import { useForm }                from '@inertiajs/inertia-vue3';
    import { useForm as useVeeForm }  from 'vee-validate';
    import * as yup                   from 'yup';

    const isSubmitting     = ref(false);
    const { handleSubmit } = useVeeForm({
        validationSchema: {
            current_password     : yup.string().required('Please enter your current password'),
            password             : yup.string().required('You must enter a password'),
            password_confirmation: yup.string().required('Enter password again'),
        }
    });

    const onSubmit = handleSubmit(form => {
        isSubmitting.value = true;
        const loginForm    = useForm(form);

        loginForm.post(route('password.store'), {
            onFinish: () => isSubmitting.value = false,
        });
    });
</script>
