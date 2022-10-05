<template>
    <Head title="Login" />
    <AuthLayout>
        <div class="row align-items-center h-100">
            <div class="col">
                <div v-if="errors.username" class="alert alert-danger text-center">
                    {{ errors.username }}
                </div>
                <form @submit="onSubmit" novalidate>
                    <TextInput id="username" label="Username" name="username" />
                    <TextInput id="passsword" label="Password" name="password" type="password" />
                    <SubmitButton :submitted="isSubmitting" />
                </form>
                <div class="form-group row justify-content-center mb-0">
                    <div class="col-md-8 text-center">
                        <Link
                            class="btn btn-link text-muted"
                            :href="route('password.forgot')"
                        >
                            Forgot Your Password?
                        </Link>
                    </div>
                </div>
            </div>
        </div>
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

    defineProps<{
        errors: { username: string },
    }>();

    const isSubmitting     = ref(false);
    const { handleSubmit } = useVeeForm({
        validationSchema: {
            username: yup.string().required('You must enter a username'),
            password: yup.string().required('You must enter a password'),
        }
    });

    const onSubmit = handleSubmit(form => {
        isSubmitting.value = true;
        const loginForm    = useForm(form);

        loginForm.post(route('login.submit'), {
            onFinish: () => isSubmitting.value = false,
        });
    });
</script>
