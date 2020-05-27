<template>
    <b-overlay :show="submitted">
        <template v-slot:overlay>
            <atom-spinner
                :animation-duration="1000"
                :size="60"
                color="#ff1d5e"
                class="mx-auto"
            />
            <h4 class="text-center">Processing...</h4>
        </template>
        <b-form @submit="validateForm" novalidate :validated="validated" ref="user-account-form">
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
                        :state="validate.state.email"
                        :class="loading.email ? 'loading' : ''"
                    ></b-form-input>
                    <b-form-invalid-feedback>Please Enter A Unique Email Address</b-form-invalid-feedback>
                </b-form-group>
            </fieldset>
            <form-submit
                button_text="Update Account Information"
                :submitted="submitted"
            ></form-submit>
        </b-form>
        <b-button :href="route('change_password')" class="mt-2 text-light" variant="warning" block>Change Password</b-button>
    </b-overlay>
</template>

<script>
    export default {
        props: {
            user_data: {
                type:     Object,
                required: true,
            }
        },
        data() {
            return {
                submitted: false,
                validated: false,
                form: {
                    first_name: this.user_data.first_name,
                    last_name:  this.user_data.last_name,
                    email:      this.user_data.email,
                },
                validate: {
                    state: {
                        email: null,
                    }
                },
                loading: {
                    email: false,
                }
            }
        },
        methods: {
            checkEmailAddress()
            {
                this.success = false;
                if(this.form.email)
                {
                    this.loading.email = true;
                    axios.get(this.route('admin.user.check', [this.form.email, 'email']))
                        .then(res => {
                            this.loading.email = false;
                            if(res.data.duplicate && res.data.username != this.user_data.username)
                            {
                                this.validate.state.email = false;
                            }
                            else
                            {
                                this.validate.state.email = true;
                            }
                        }).catch(error => this.$bvModal.msgBoxOk('Unable to determine if this email address is in use.  Please try again later.'));
                }
            },
            validateForm(e)
            {
                e.preventDefault();
                if(this.$refs['user-account-form'].checkValidity() === false)
                {
                     this.validated = true;
                }
                else
                {
                    this.submitted = true;
                    axios.post(this.route('update_account'), this.form)
                        .then(res => {
                            if(res.data.success)
                            {
                                this.submitted = false;
                                this.validated = false;
                                this.$bvModal.msgBoxOk('Account Updated');
                            }
                            else
                            {
                                this.$bvModal.msgBoxOk('Unable to update account settings.  Please try again later.');
                            }
                        }).catch(error => this.$bvModal.msgBoxOk('Unable to update account settings.  Please try again later.'));
                }
            }
        }
    }
</script>
