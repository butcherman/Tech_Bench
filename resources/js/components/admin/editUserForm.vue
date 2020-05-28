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
        <div class="row justify-content-center" v-show="success">
            <div class="col-md-8">
                <b-alert variant="success" class="text-center" show><h3>{{success}}</h3></b-alert>
            </div>
        </div>
        <b-form @submit="validateForm" ref="new-user-form" :validated="validated" novalidate>
            <div class="row">
                <div class="col-md-6">
                    <b-form-group label="Username:" label-for="username">
                        <b-form-input
                            id="username"
                            type="text"
                            v-model="form.username"
                            required
                            placeholder="Enter A Unique Username"
                            autocomplete="false"
                            :state="validate.state.username"
                            :class="loading.username ? 'loading' : ''"
                            @blur="checkForDup('username')"
                        ></b-form-input>
                        <b-form-invalid-feedback v-if="validate.message.username">
                            <div v-for="msg in validate.message.username" :key="msg">
                                {{msg}}
                            </div>
                        </b-form-invalid-feedback>
                    </b-form-group>
                </div>
                <div class="col-md-6">
                    <b-form-group label="Email Address:" label-for="email">
                        <b-form-input
                            id="email"
                            type="email"
                            v-model="form.email"
                            required
                            placeholder="johedoe@example.com"
                            :state="validate.state.email"
                            :class="loading.email ? 'loading' : ''"
                            @blur="checkForDup('email')"
                        ></b-form-input>
                        <b-form-invalid-feedback v-if="validate.message.email">
                            <div v-for="msg in validate.message.email" :key="msg">
                                {{msg}}
                            </div>
                        </b-form-invalid-feedback>
                    </b-form-group>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <b-form-group label="First Name:" label-for="first_name">
                        <b-form-input
                            id="first_name"
                            type="text"
                            v-model="form.first_name"
                            required
                            placeholder="Example - John"
                            :state="validate.state.first_name"
                            @blur="validateInput('first_name')"
                        ></b-form-input>
                        <b-form-invalid-feedback v-if="validate.message.first_name">
                            <div v-for="msg in validate.message.first_name" :key="msg">
                                {{msg}}
                            </div>
                        </b-form-invalid-feedback>
                    </b-form-group>
                </div>
                <div class="col-md-6">
                    <b-form-group label="Last Name:" label-for="last_name">
                        <b-form-input
                            id="last_name"
                            type="text"
                            v-model="form.last_name"
                            required
                            placeholder="Example - Doe"
                            :state="validate.state.last_name"
                            @blur="validateInput('last_name')"
                        ></b-form-input>
                        <b-form-invalid-feedback v-if="validate.message.last_name">
                            <div v-for="msg in validate.message.last_name" :key="msg">
                                {{msg}}
                            </div>
                        </b-form-invalid-feedback>
                    </b-form-group>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <b-form-group label="User Role:" label-for="role">
                        <b-form-select
                            id="role"
                            type="text"
                            v-model="form.role_id"
                            :options="role_list"
                            value-field="role_id"
                            text-field="name"
                            required
                        ></b-form-select>
                        <b-form-invalid-feedback>This field is required</b-form-invalid-feedback>
                    </b-form-group>
                </div>
            </div>
            <form-submit
                button_text="Update User"
                :submitted="submitted"
            ></form-submit>
        </b-form>
    </b-overlay>
</template>

<script>
    export default {
        props: {
            role_list: {
                type:     Array,
                required: true,
            },
            user_details: {
                type:     Object,
                required: true,
            }
        },
        data() {
            return {
                submitted: false,
                validated: false,
                success:   null,
                form: {
                    user_id:    this.user_details.user_id,
                    username:   this.user_details.username,
                    email:      this.user_details.email,
                    first_name: this.user_details.first_name,
                    last_name:  this.user_details.last_name,
                    role_id:    this.user_details.role_id,
                },
                validate: {
                    state: {
                        username:   null,
                        email:      null,
                        first_name: null,
                        last_name:  null,
                    },
                    message: {
                        username:   ['Username field is required'],
                        email:      ['Email field is required'],
                        first_name: ['First Name field is required'],
                        last_name:  ['Last Name field is required'],
                    }
                },
                loading: {
                    username: false,
                    email:    false,
                }
            }
        },
        methods: {
            //  Check for a duplicate username or email address
            checkForDup(type)
            {
                if(this.form[type] && this.form[type] != this.user_details[type])
                {
                    this.loading[type] = true;
                    axios.get(this.route('admin.user.check', [this.form[type], type]))
                        .then(res => {
                            this.loading[type] = false;
                            if(res.data.duplicate)
                            {
                                this.validate.state[type]   = false;
                                this.validate.message[type][0] = type+' is currently in use by '+res.data.user;
                                if(!res.data.active)
                                {
                                    this.validate.message[type][1] = 'Note - this user has been deactivated';
                                }
                            }
                            else
                            {
                                this.validate.state[type] = true;
                            }
                        }).catch(error => this.eventHub.$emit('axiosError', error));
                }
                else
                {
                    this.validateInput(type)
                }
            },
            //  Validate the infividual inputs that do not require axios requests
            validateInput(field)
            {
                if(this.form[field])
                {
                    this.validate.state[field] = true;
                }
                else
                {
                    this.validate.state[field] = null
                }
            },
            //  Validate and submit the form
            validateForm(e)
            {
                e.preventDefault();
                if(this.$refs['new-user-form'].checkValidity() === 'false')
                {
                     this.validated = true;
                }
                else
                {
                     this.submitted = true;
                     axios.post(this.route('admin.user.update', this.user_details.user_id), this.form)
                            .then(res => {
                                location.href = this.route('admin.user.active_users');
                            }).catch(error => {
                                this.submitted = false;
                                if(error.response.status === 422)
                                {
                                    this.validate.message = error.response.data.errors;

                                    var errors = error.response.data.errors;
                                    this.validate.state.username   = errors.username   ? false : true;
                                    this.validate.state.email      = errors.email      ? false : true;
                                    this.validate.state.first_name = errors.first_name ? false : true;
                                    this.validate.state.last_name  = errors.last_name  ? false : true;
                                }
                                else
                                {
                                    this.eventHub.$emit('axiosError', error);
                                }
                            });
                }
            },
        }
    }
</script>
