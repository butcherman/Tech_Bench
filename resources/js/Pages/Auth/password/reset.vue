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
                    <!-- <ValidationProvider v-slot="v" rules="required|confirmed:confirmation|min:6">
                        <b-form-group
                            label="Password:"
                            label-for="password"
                        >
                            <b-form-input
                                id="password"
                                name="password"
                                type="password"
                                placeholder="Enter New Password"
                                v-model="form.password"
                            ></b-form-input>
                            <b-form-invalid-feedback :state="false">{{v.errors[0]}}</b-form-invalid-feedback>
                        </b-form-group>
                    </ValidationProvider>
                    <ValidationProvider v-slot="v" vid="confirmation" rules="required|min:6">
                        <b-form-group
                            label="Confirm Password:"
                            label-for="password_confirmation"
                        >
                            <b-form-input
                                id="password_confirmation"
                                name="password_confirmation"
                                type="password"
                                placeholder="Confirm New Password"
                                v-model="form.password_confirmation"
                            ></b-form-input>
                            <b-form-invalid-feedback :state="false">{{v.errors[0]}}</b-form-invalid-feedback>
                        </b-form-group>
                    </ValidationProvider> -->
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
            email: {
                type: String,
                required: true,
            },
            token: {
                type: String,
                required: true,
            },
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
