<template>
    <div>
        <h6 class="text-center">Enter your email address for instructions on accessing your account.</h6>
        <b-alert variant="danger" :show="errors.email ? true : false">
            <p class="text-center">This Email Address Does Not Match Our Records</p>
        </b-alert>
        <b-alert variant="success" :show="$page.props.flash.message ? true : false">
            <p class="text-center">Please Check Your Email For Instructions</p>
        </b-alert>
        <ValidationObserver v-slot="{handleSubmit}">
            <b-form @submit.prevent="handleSubmit(submitForm)" novalidate>
                <text-input v-model="form.email" rules="required|email" label="Email Address" name="email" type="email"></text-input>
                <submit-button button_text="Sent Password Reset Link" :submitted="submitted"></submit-button>
            </b-form>
        </ValidationObserver>
    </div>
</template>

<script>
    import Guest from '../../Layouts/guest';
    import Auth  from '../../Layouts/Nested/authLayout';

    export default {
        layout: [Guest, Auth],
        props: {
            errors: Object,
        },
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
                this.$inertia.post(route('forgot-password'), this.form, {onFinish: () => {this.submitted = false}});
            }
        },
        metaInfo: {
            title: 'Welcome',
        }
    }
</script>
