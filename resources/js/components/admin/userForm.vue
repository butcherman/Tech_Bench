<template>
    <b-form @submit="validateForm" novalidate :validated="validated" ref="userForm">
        <div class="row justify-content-center" v-show="success">
            <div class="col-md-8">
                <b-alert variant="success" class="text-center" show><h3>{{success}}</h3></b-alert>
            </div>
        </div>
        <fieldset :disabled="submitted">
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
                            @blur="checkUsername"
                            :state="validate.state.username"
                            :class="validate.class.username"
                        ></b-form-input>
                        <b-form-invalid-feedback>{{validate.msg.username}}</b-form-invalid-feedback>
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
                            @blur="checkEmail"
                            :state="validate.state.email"
                            :class="validate.class.email"
                        ></b-form-input>
                        <b-form-invalid-feedback>{{validate.msg.email}}</b-form-invalid-feedback>
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
                        ></b-form-input>
                        <b-form-invalid-feedback>This field is required</b-form-invalid-feedback>
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
                        ></b-form-input>
                        <b-form-invalid-feedback>This field is required</b-form-invalid-feedback>
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
                            required
                        ></b-form-select>
                        <b-form-invalid-feedback>This field is required</b-form-invalid-feedback>
                    </b-form-group>
                </div>
            </div>
        </fieldset>
        <form-submit
            :button_text="button"
            :submitted="submitted"
        ></form-submit>
    </b-form>
</template>

<script>
    export default {
        props: {
            role_list: {
                type:     Array,
                required: true,
            },
            edit: {
                type:     Object,
                required: false,
            }
        },
        data() {
            return {
                validated: false,
                submitted: false,
                success:   null,
                form: {
                    username:   null,
                    email:      null,
                    first_name: null,
                    last_name:  null,
                    role_id:    4,
                },
                validate: {
                    state: {
                        username: null,
                        email:    null,
                    },
                    msg: {
                        username: 'Enter A Valid Username',
                        email:    'Enter A Valid Email Address',
                    },
                    class: {
                        username: null,
                        email:    null,
                    }
                },
                button: 'Create New User and Send Welcome Email',
            }
        },
        created() {
            //
        },
        mounted() {
             if(this.edit)
             {
                 this.form   = this.edit;
                 this.button = 'Update User';
             }

             console.log(this.edit);
        },
        computed: {
             //
        },
        watch: {
             //
        },
        methods: {
            //  Validate the submitted form
            validateForm(e)
            {
                e.preventDefault();
                console.log(this.form);
                if(this.$refs.userForm.checkValidity() === false || this.validate.state.username === false || this.validate.state.email === false)
                {
                    this.validated = true;
                }
                else
                {
                    this.submitted = true;

                    if(this.edit)
                    {
                        console.log('edit form');
                        axios.put(this.route('admin.user.update', this.edit.user_id), this.form)
                            .then(res => {
                                if(res.data.success)
                                {
                                    this.success   = 'User Updated Successfully';
                                    this.submitted = false;
                                    this.button    = 'Update User';
                                }
                            // }).catch(error => this.$bvModal.msgBoxOk('Unable to update user at this time'));
                            }).catch(error => console.log(error.response));
                    }
                    else
                    {
                        axios.post(this.route('admin.user.store'), this.form)
                            .then(res => {
                                this.success = 'User '+this.form.first_name+' '+this.form.last_name+' created';
                                this.resetForm();
                            }).catch(error => this.$bvModal.msgBoxOk('Unable to add user at this time'));
                    }
                }

            },
            //  Check that the given username is not already in use
            checkUsername()
            {
                if(this.form.username)
                {
                    if(!/^[0-9A-Z]*$/i.test(this.form.username))
                    {
                        this.validate.state.username = false;
                        this.validate.msg.username = 'Username can only contain letters and numbers';
                    }
                    else
                    {
                        this.validate.class.username = 'loading';
                        axios.get(this.route('admin.checkUser', [this.form.username, 'username']))
                            .then(res => {
                                console.log(res);
                                this.validate.class.username = null;
                                if(res.data.duplicate)
                                {
                                    if(this.edit != null)
                                    {
                                        if(res.data.user !== this.edit.full_name)
                                        {
                                            this.validate.msg.username = 'This username is taken by '+res.data.user+'.';
                                            if(res.data.active == 0)
                                            {
                                                this.validate.msg.username = this.validate.msg.username+'  Note - this user has been deactivated';
                                            }
                                            this.validate.state.username = false;
                                        }
                                        else
                                        {
                                            this.validate.state.username = true;
                                            this.validate.msg.username   = null;
                                        }
                                    }
                                    else
                                    {
                                        this.validate.msg.username = 'This username is taken by '+res.data.user+'.';
                                        if(res.data.active == 0)
                                        {
                                            this.validate.msg.username = this.validate.msg.username+'  Note - this user has been deactivated';
                                        }
                                        this.validate.state.username = false;
                                    }
                                }
                                else
                                {
                                    this.validate.state.username = true;
                                    this.validate.msg.username   = null;
                                }
                            }).catch(error => this.bvModal.msgBoxOk('Something bad happened.  Please try again later.'));
                    }
                }
                else
                {
                    this.validate.state.username = null;
                    this.validate.msg.username   = 'Enter A Valid Username';
                }
            },
            //  Check that the given email address is not already in use
            checkEmail()
            {
                if(this.form.email)
                {
                    if(!/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,24}))$/i.test(this.form.email))
                    {
                        this.validate.state.email = false;
                        this.validate.msg.email = 'Please enter a valid Email Address';
                    }
                    else
                    {
                        this.validate.class.email = 'loading';
                        axios.get(this.route('admin.checkUser', [this.form.email, 'email']))
                            .then(res => {
                                console.log(res);
                                this.validate.class.email = null;
                                if(res.data.duplicate)
                                {
                                    if(this.edit)
                                    {
                                        if(res.data.user !== this.edit.full_name)
                                        {
                                            this.validate.msg.email = 'This Email Address is taken by '+res.data.user+'.';
                                            if(res.data.active == 0)
                                            {
                                                this.validate.msg.email = this.validate.msg.email+'  Note - this user has been deactivated';
                                            }
                                            this.validate.state.email = false;
                                        }
                                        else
                                        {
                                            this.validate.state.email = true;
                                            this.validate.msg.email   = null;
                                        }
                                    }
                                    else
                                    {
                                        this.validate.msg.email = 'This Email Address is taken by '+res.data.user+'.';
                                        if(res.data.active == 0)
                                        {
                                            this.validate.msg.email = this.validate.msg.email+'  Note - this user has been deactivated';
                                        }
                                        this.validate.state.email = false;
                                    }
                                }
                                else
                                {
                                    this.validate.state.email = true;
                                    this.validate.msg.email   = null;
                                }
                            }).catch(error => this.bvModal.msgBoxOk('Something bad happened.  Please try again later.'));
                    }
                }
                else
                {
                    this.validate.state.email = null;
                    this.validate.msg.email   = 'Enter A Valid Email Address';
                }
            },
            //  Reset the user form so it can be used again
            resetForm()
            {
                this.submitted               = false;
                this.validated               = false;
                this.validate.msg.username   = 'Enter A Valid Username',
                this.validate.msg.email      = 'Enter A Valid Email Address',
                this.validate.state.username = null,
                this.validate.state.email    = null,
                this.button                  = 'Create New User and Send Welcome Email';
                this.form.username           = null;
                this.form.email              = null;
                this.form.first_name         = null;
                this.form.last_name          = null;
            }
        }
    }
</script>
