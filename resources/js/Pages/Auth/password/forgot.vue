<template>
    <div class="row h-100 align-items-center">
        <div class="col-12">
            <h6 class="text-center">Enter your email address for instructions on accessing your account.</h6>
            <b-alert variant="success" :show="$page.props.flash.message ? true : false">
                <p class="text-center">Please Check Your Email For Instructions</p>
            </b-alert>
            <ValidationObserver v-slot="{handleSubmit}">
                <b-form @submit.prevent="handleSubmit(submitForm)" novalidate>
                    <text-input v-model="form.email" rules="required|email" label="Email Address" name="email" type="email" placeholder="Email Address"></text-input>
                    <submit-button button_text="Send Password Reset Link" class="mb-2" :submitted="submitted"></submit-button>
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
        data() {
            return {
                form: {
                    email: '',
                },
                submitted: false,
            }
        },
        methods: {
            submitForm()
            {
                this.submitted = true;
                this.$inertia.post(route('password.submit-email'), this.form, {onFinish: () => {this.submitted = false}});
            }
        },
        metaInfo: {
            title: 'Forgot Password',
        }
    }
</script>
