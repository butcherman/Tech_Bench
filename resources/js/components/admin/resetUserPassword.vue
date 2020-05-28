<template>
    <b-overlay :show="submitted">
        <template v-slot:overlay>
            <atom-spinner
                :animation-duration="1000"
                :size="60"
                color="#ff1d5e"
                class="mx-auto"
            />
            <h4 class="text-center">Processing</h4>
        </template>
        <b-modal :title="modalTitle" id="password-form-modal" ref="password-form-modal" hide-footer @hidden="resetForm">
            <b-form @submit="validateForm" novalidate :validated="validated" ref="reset-password-form">
                <fieldset :disabled="submitted">
                    <b-form-group label="New Password:" label-for="password">
                        <b-form-input
                            id="password"
                            type="password"
                            v-model="form.password"
                            :state="passwordState"
                            required
                            placeholder="Enter User's New Password"
                        ></b-form-input>
                        <b-form-invalid-feedback>
                            <div v-for="msg in valid.msg.password" :key="msg">
                                {{msg}}
                            </div>
                        </b-form-invalid-feedback>
                    </b-form-group>
                    <b-form-group label="Confirm Password:" label-for="password_confirmed">
                        <b-form-input
                            id="password_confirmed"
                            type="password"
                            v-model="form.password_confirmation"
                            :state="confirmedState"
                            required
                            placeholder="Confirm User's New Password"
                        ></b-form-input>
                        <b-form-invalid-feedback>
                            <div v-for="msg in valid.msg.password_confirmation" :key="msg">
                                {{msg}}
                            </div>
                        </b-form-invalid-feedback>
                    </b-form-group>
                    <b-form-checkbox switch v-model="form.force_change">Force User to Change Password on Next Login</b-form-checkbox>
                    <div class="text-center" v-show="generated"><strong>Please Note New Password:</strong></div>
                    <div class="text-center" v-show="generated">{{generated}}</div>
                    <b-button block variant="warning" class="mt-3 mb-3" @click="generatePassword">Generate Random Password</b-button>
                </fieldset>
                <form-submit
                    button_text="Reset Password"
                    :submitted="submitted"
                ></form-submit>
            </b-form>
        </b-modal>
    </b-overlay>
</template>

<script>
    export default {
        data() {
            return {
                modalTitle: 'Change Password',
                generated: false,
                submitted: false,
                validated: false,
                name:      null,
                form: {
                    password: null,
                    password_confirmation: null,
                    user_id: null,
                    force_change: true,
                },
                valid: {
                    msg: {
                        password:              null,
                        password_confirmation: null,
                    }
                }
            }
        },
        computed: {
             passwordState()
             {
                 return this.valid.msg.password ? false : null;
             },
             confirmedState()
             {
                 return this.valid.msg.password_confirmed ? false : null;
             },
        },
        methods: {
            openForm(name, user_id)
            {
                this.modalTitle   = 'Reset password for '+name;
                this.form.user_id = user_id;
                this.name         = name;
                this.$refs['password-form-modal'].show();
            },
            generatePassword()
            {
                var generator                   = require('generate-password');
                this.generated                  = generator.generate();
                this.form.password              = this.generated;
                this.form.password_confirmation = this.generated;
            },
            resetForm()
            {
                this.modalTitle                 = 'Change Password';
                this.generated                  = false;
                this.submitted                  = false;
                this.validated                  = false;
                this.name                       = null;
                this.form.password              = null;
                this.form.password_confirmation = null;
                this.form.user_id               = null;
                this.form.force_change          = true;
                this.form.password              = null;
                this.form.password_confirmation = null;
            },
            validateForm(e)
            {
                e.preventDefault();
                if(this.$refs['reset-password-form'].checkValidity() === false)
                {
                     this.validated = true;
                }
                else
                {
                     this.submitted = true;
                     axios.post(this.route('admin.user.change_password'), this.form)
                         .then(res => {
                            this.$refs['password-form-modal'].hide();
                            this.$bvModal.msgBoxOk('Password has been reset for '+this.name);
                         }).catch(error => {
                            this.submitted = false;
                            if(error.response.status === 422)
                            {
                                this.valid.msg = error.response.data.errors;
                            }
                            else
                            {
                                this.eventHub.$emit('axiosError', error);
                            }
                         });
                }
            }
        }
    }
</script>
