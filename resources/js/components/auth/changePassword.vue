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
        <b-form @submit="validateForm" ref="change-password-form" :validated="validated" novalidate>
            <b-form-group label="Current Password:" label-for="current">
                <b-form-input
                    id="current"
                    type="password"
                    v-model="form.current"
                    :state="currentState"
                    required
                ></b-form-input>
                <b-form-invalid-feedback>
                    <div v-for="msg in valid.msg.current" :key="msg">
                        {{msg}}
                    </div>
                </b-form-invalid-feedback>
            </b-form-group>
            <b-form-group label="New Password:" label-for="password">
                <b-form-input
                    id="password"
                    type="password"
                    v-model="form.password"
                    :state="passwordState"
                    required
                ></b-form-input>
                <b-form-invalid-feedback>
                    <div v-for="msg in valid.msg.password" :key="msg">
                        {{msg}}
                    </div>
                </b-form-invalid-feedback>
            </b-form-group>
            <b-form-group label="New Password:" label-for="confirmed">
                <b-form-input
                    id="confirmed"
                    type="password"
                    v-model="form.password_confirmation"
                    :state="confirmedState"
                    required
                ></b-form-input>
                <b-form-invalid-feedback>
                    <div v-for="msg in valid.msg.password_confirmation" :key="msg">
                        {{msg}}
                    </div>
                </b-form-invalid-feedback>
            </b-form-group>
            <form-submit
                button_text="Update Password"
                :submitted="submitted"
            ></form-submit>
        </b-form>
    </b-overlay>
</template>

<script>
    export default {
        data() {
            return {
                submitted: false,
                validated: false,
                form: {
                    curent:                null,
                    password:              null,
                    password_confirmation: null,
                },
                valid: {
                    msg: {
                        current:               null,
                        password:              null,
                        password_confirmation: null,
                    }
                }
            }
        },
        computed: {
             currentState()
             {
                 return this.valid.msg.current ? false : null;
             },
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
            validateForm(e)
            {
                e.preventDefault();
                if(this.$refs['change-password-form'].checkValidity() === false)
                {
                     this.validated = true;
                }
                else
                {
                     this.submitted = true;
                     axios.post(this.route('submit_password'), this.form)
                         .then(res => {
                             this.$bvModal.msgBoxOk('Your Password Has Been Updated Successfully')
                                .then(res => {
                                    location.href = this.route('dashboard');
                                });
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
