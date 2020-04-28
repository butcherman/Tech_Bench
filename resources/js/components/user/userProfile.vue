<template>
    <div>
        <b-form @submit="validateForm" novalidate :validated="validated" ref="userSettingsForm">
            <fieldset :disabled="submitted">
                <b-form-group label="First Name:" label-for="first_name">
                    <b-form-input
                        id="first_name"
                        type="text"
                        v-model="form.first_name"
                        required
                    ></b-form-input>
                    <b-form-invalid-feedback>Please Enter Your First Name</b-form-invalid-feedback>
                </b-form-group>
                <b-form-group label="Last Name:" label-for="last_name">
                    <b-form-input
                        id="last_name"
                        type="text"
                        v-model="form.last_name"
                        required
                    ></b-form-input>
                    <b-form-invalid-feedback>Please Enter Your Last Name</b-form-invalid-feedback>
                </b-form-group>
                <b-form-group label="Email Address:" label-for="email">
                    <b-form-input
                        id="email"
                        type="email"
                        v-model="form.email"
                        required
                        @blur="checkEmailAddress"
                        :state="error.email"
                        :class="loading.email ? 'loading' : ''"
                    ></b-form-input>
                    <b-form-invalid-feedback>Please Enter A Unique Email Address</b-form-invalid-feedback>
                </b-form-group>
            </fieldset>
            <form-submit
                class="mt-3"
                button_text="Update User Information"
                :submitted="submitted"
            ></form-submit>
        </b-form>
    </div>
</template>

<script>
export default {
    props: [
        'user_data'
    ],
    data() {
        return {
            validated: false,
            submitted: false,
            form: {
                first_name: this.user_data.first_name,
                last_name:  this.user_data.last_name,
                email:      this.user_data.email,
            },
            loading: {
                email: false,
            },
            error: {
                email: null,
            }
        }
    },
    created() {
        //
    },
    methods: {
        validateForm(e)
        {
            e.preventDefault();
            if(this.$refs.userSettingsForm.checkValidity() === false)
            {
                this.validated = true;
            }
            else
            {
                this.submitted = true;
                axios.post(this.route('account'), this.form)
                    .then(res => {
                        if(res.data.success)
                        {
                            this.submitted = false;
                            this.validated = false;
                            this.$bvModal.msgBoxOk('User Settings Updated');
                        }
                        else
                        {
                            this.$bvModal.msgBoxOk('Unable to update user settings.  Please try again later.');
                        }
                    }).catch(error => this.$bvModal.msgBoxOk('Unable to update user settings.  Please try again later.'));
            }
        },
        checkEmailAddress()
        {
            this.success = false;
            if(this.form.email)
            {
                this.loading.email = true;
                axios.get(this.route('admin.checkUser', [this.form.email, 'email']))
                    .then(res => {
                        this.loading.email = false;
                        if(res.data.duplicate && res.data.username != this.user_data.username)
                        {
                            this.error.email = false;
                        }
                        else
                        {
                            this.error.email = true;
                        }
                    }).catch(error => this.$bvModal.msgBoxOk('Unable to determine if this email address is in use.  Please try again later.'));
            }
        }
    }
}
</script>
