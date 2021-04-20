<template>
    <ValidationObserver v-slot="{handleSubmit}">

        <h5 class="text-center">Welcome {{name}}</h5>
        <h6 class="text-center">Enter your email and create a password to get started</h6>

        <b-alert variant="danger" :show="errors ? true : false" v-for="e in errors" :key="e">
            <p class="text-center">{{e}}</p>
        </b-alert>

        <b-form @submit.prevent="handleSubmit(submitForm)" novalidate>
            <text-input v-model="form.email" rules="required|email" label="Email" name="email" :value="form.email"></text-input>
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
</template>

<script>
    import Guest from '../../Layouts/guest';
    import Auth  from '../../Layouts/Nested/authLayout';

    export default {
        layout: [Guest, Auth],
        props: {
            token: {
                type: String,
                required: true,
            },
            name: {
                type: String,
                required: true,
            }
        },
        data() {
            return {
                form: {
                    email: '',
                    password: '',
                    password_confirmation: '',
                },
                submitted: false,
            }
        },
        methods: {
            submitForm()
            {
                this.$inertia.put(route('initialize.update', this.token), this.form);
            }
        }
    }
</script>
