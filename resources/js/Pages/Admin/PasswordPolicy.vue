<template>
    <div>
        <div class="row">
            <div class="col-12 grid-margin">
                <h4 class="text-center text-md-left">Password Policy</h4>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-6 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <ValidationObserver v-slot="{handleSubmit}">
                            <b-overlay :show="submitted">
                                <template #overlay>
                                    <form-loader text="Updating Policy..."></form-loader>
                                </template>
                                <b-form @submit.prevent="handleSubmit(submitForm)" novalidate>
                                    <text-input v-model="form.password_expires" rules="required" label="Password Expires in Days (enter 0 for no expiration)" name="password_expires"></text-input>
                                    <submit-button button_text="Update Password Policy" :submitted="submitted" class="mt-3" />
                                </b-form>
                            </b-overlay>
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
            password_expires: {
                type:     Number|String,
                required: true,
            }
        },
        data() {
            return {
                submitted: false,
                form: this.$inertia.form({
                    password_expires: this.password_expires,
                }),
            }
        },
        methods: {
            submitForm()
            {
                this.submitted = true;
                this.form.put(route('admin.set-password-policy'), {
                    onSuccess: ()=> {
                        this.submitted = false;
                    }
                });
            }
        }
    }
</script>
