<template>
    <div class="row h-100 align-items-center">
        <div class="col-12">
            <ValidationObserver v-slot="{handleSubmit}">
                <h6 class="text-center">Reset Password</h6>
                <b-alert variant="danger" :show="errors ? true : false" v-for="e in errors" :key="e">
                    <p class="text-center">{{e}}</p>
                </b-alert>
                <b-form @submit.prevent="handleSubmit(submitForm)" novalidate>
                    <text-input v-model="form.email" rules="required|email" label="Email" name="email" :value="form.email"></text-input>
                    <text-input v-model="form.password" type="password" rules="required|confirmed:confirmation|min:6" label="New Password" name="password" placeholder="Enter New Password"></text-input>
                    <text-input v-model="form.password_confirmation" type="password" rules="required" vid="confirmation" label="Confirm Password" name="password_confirmation" placeholder="Confirm New Password"></text-input>
                    <submit-button button_text="Reset Password" class="mb-2" :submitted="submitted"></submit-button>
                </b-form>
            </ValidationObserver>
        </div>
    </div>
</template>

<script>
    import Guest from '../../../Layouts/guest';
    import Auth  from '../../../Layouts/Nested/authLayout';

    export default {
        layout: [Guest, Auth],
        props: {
            /**
             * Users email address
             */
            email: {
                type: String,
                required: true,
            },
            /**
             * reference token used from teh password_resets table
             */
            token: {
                type: String,
                required: true,
            },
            /**
             * validation errors from the server
             */
            errors: {
                type: Object,
            }
        },
        data() {
            return {
                form: {
                    token:                 this.token,
                    email:                 this.email,
                    password:              '',
                    password_confirmation: '',
                },
                submitted: false,
            }
        },
        methods: {
            submitForm()
            {
                this.submitted = true;
                this.$inertia.post(route('password.reset-submit'), this.form, {onFinish: () => {this.submitted = false}});
            }
        },
        metaInfo: {
            title: 'Reset Password',
        }
    }
</script>
