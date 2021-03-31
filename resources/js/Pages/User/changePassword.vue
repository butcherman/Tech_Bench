<template>
    <div>
        <div class="row">
            <div class="col-12 grid-margin">
                <h4 class="text-center text-md-left">Change Password</h4>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Please Enter New Password</div>
                        <ValidationObserver v-slot="{handleSubmit}">
                            <b-form @submit.prevent="handleSubmit(submitForm)" novalidate>
                                <ValidationProvider v-slot="v" rules="required|confirmed:confirmation|min:6">
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
                                </ValidationProvider>
                                <submit-button button_text="Reset Password" :submitted="submitted"></submit-button>
                            </b-form>
                        </ValidationObserver>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import App from '../../Layouts/app';

    export default {
        layout: App,
        props: {
            //
        },
        data() {
            return {
                //
                form: {
                    password: '',
                    password_confirmation: '',
                },
                submitted: false,
            }
        },
        created() {
            //
        },
        mounted() {
             //
        },
        computed: {
             //
        },
        watch: {
             //
        },
        methods: {
            //
            submitForm()
            {
                this.submitted = true;
                this.$inertia.put(route('password.update', this.$page.props.user.username), this.form, {onFinish: () => {this.submitted = false}});
            }
        }
    }
</script>
