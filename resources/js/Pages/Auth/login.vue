<template>
    <ValidationObserver v-slot="{handleSubmit}">
        <b-alert :variant="$page.props.flash.type" :show="$page.props.flash.message ? true : false">
            <p class="text-center">{{$page.props.flash.message}}</p>
        </b-alert>
        <b-form @submit.prevent="handleSubmit(submitForm)" novalidate>
            <text-input v-model="form.username" rules="required" label="Username" name="username" placeholder="Username" autofocus></text-input>
            <text-input v-model="form.password" rules="required" label="Password" name="password" type="password" placeholder="Password"></text-input>
            <b-checkbox switch class="no-validate" name="remember" v-model="form.remember">Remember Me</b-checkbox>
            <submit-button button_text="Login" :submitted="submitted"></submit-button>
            <div class="form-group row justify-content-center mb-0">
                <div class="col-md-8 text-center">
                    <inertia-link class="btn btn-link text-muted" :href="route('password.forgot')">Forgot Your Password?</inertia-link>
                </div>
            </div>
        </b-form>
    </ValidationObserver>
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
                form: this.$inertia.form({
                    username: null,
                    password: null,
                    remember: false,
                }),
                submitted: false,
            }
        },
        methods: {
            submitForm()
            {
                this.submitted = true;
                this.$inertia.post(route('login.submit'), this.form, {onFinish: () => {this.submitted = false}});
            }
        },
        metaInfo: {
            title: 'Login',
        }
    }
</script>


