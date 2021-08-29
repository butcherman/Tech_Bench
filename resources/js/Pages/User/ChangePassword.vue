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
                                <text-input v-model="form.current_password" type="password" rules="required" label="Current Password" name="current_password" placeholder="Enter Current Password" autofocus></text-input>
                                <text-input v-model="form.password" type="password" rules="required|confirmed:confirmation|min:6" label="New Password" name="password" placeholder="Enter New Password"></text-input>
                                <text-input v-model="form.password_confirmation" type="password" rules="required" vid="confirmation" label="Confirm Password" name="password_confirmation" placeholder="Confirm New Password"></text-input>
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
        data() {
            return {
                form: {
                    current_password:      '',
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
                this.$inertia.post(route('password.store'), this.form, {onFinish: () => {this.submitted = false}});
            }
        },
        metaInfo: {
            title: 'Change Password',
        }
    }
</script>
