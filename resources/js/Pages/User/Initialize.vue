<template>
    <div>
        <h5 class="text-center text-dark">Welcome {{name}}</h5>
        <h6 class="text-center">Create a password to finish setting up your account</h6>
        <ValidationObserver v-slot="{handleSubmit}">
            <b-form @submit.prevent="handleSubmit(submitForm)" novalidate>
                <text-input v-model="form.username" rules="required" label="Username" name="username" :value="form.username" disabled></text-input>
                <text-input v-model="form.password" type="password" rules="required|confirmed:confirmation|min:6" label="New Password" name="password" placeholder="Enter New Password"></text-input>
                <text-input v-model="form.password_confirmation" type="password" rules="required" vid="confirmation" label="Confirm Password" name="password_confirmation" placeholder="Confirm New Password"></text-input>
                <submit-button button_text="Finish Setup" :submitted="submitted"></submit-button>
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
            link: {
                type:     Object,
                required: true,
            },
            name: {
                type:     String,
                required: true,
            }
        },
        data() {
            return {
                submitted: false,
                form:      this.$inertia.form({
                    username:              this.link.username,
                    password:              null,
                    password_confirmation: null,
                }),
            }
        },
        methods: {
            submitForm()
            {
                this.submitted = true;
                this.form.put(route('finish-setup', this.link.token));
            }
        }
    }
</script>
