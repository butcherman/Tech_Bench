<template>
    <Head title="Reset Password" />
    <AuthLayout>
        <h6 class="text-center">Reset Password</h6>
        <form @submit="onSubmit" novalidate>
            <TextInput id="email" label="Email Address" name="email" />
            <TextInput id="password" type="password" label="New Password" name="password" />
            <TextInput id="password-confirmation" type="password" label="Confirm Password" name="password_confirmation" />
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

    const props = defineProps<{
        email : string;
        token : string;
    }>();

    const isSubmitting     = ref(false);
    const { handleSubmit } = useVeeForm({
        validationSchema: {
            email                : yup.string().email().required('You must enter an email address'),
            password             : yup.string().required('You must enter a password'),
            password_confirmation: yup.string().required('Enter password again')
                                    //   .oneOf([yup.ref('password')], 'Passwords do not match'),
        },
        initialValues: {
            token: props.token,
            email: props.email,
        }
    });

    const onSubmit = handleSubmit(form => {
        isSubmitting.value = true;
        const resetForm    = useForm(form);

        resetForm.post(route('password.reset-submit'), {
            onFinish: () => isSubmitting.value = false,
        });
    });


    // import Guest from '@/Components/Layouts/guest';
    // import Auth  from '../../../Layouts/Nested/authLayout';

    // export default {
    //     layout: [Guest, Auth],
    //     props: {
    //         /**
    //          * Users email address
    //          */
    //         email: {
    //             type: String,
    //             required: true,
    //         },
    //         /**
    //          * reference token used from teh password_resets table
    //          */
    //         token: {
    //             type: String,
    //             required: true,
    //         },
    //         /**
    //          * validation errors from the server
    //          */
    //         errors: {
    //             type: Object,
    //         }
    //     },
    //     data() {
    //         return {
    //             form: {
    //                 token:                 this.token,
    //                 email:                 this.email,
    //                 password:              '',
    //                 password_confirmation: '',
    //             },
    //             submitted: false,
    //         }
    //     },
    //     methods: {
    //         submitForm()
    //         {
    //             this.submitted = true;
    //             this.$inertia.post(route('password.reset-submit'), this.form, {onFinish: () => {this.submitted = false}});
    //         }
    //     },
    //     metaInfo: {
    //         title: 'Reset Password',
    //     }
    // }
</script>
