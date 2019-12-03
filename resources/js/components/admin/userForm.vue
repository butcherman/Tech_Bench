<template>
    <b-form @submit="validateForm" novalidate :validated="validated" ref="userForm">
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
                        :state="error.bool.username"
                        :class="loading.username ? 'loading' : ''"
                    ></b-form-input>
                    <b-form-invalid-feedback>{{error.username}}</b-form-invalid-feedback>
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
                        :state="error.bool.email"
                        :class="loading.email ? 'loading' : ''"
                    ></b-form-input>
                    <b-form-invalid-feedback>{{error.email}}</b-form-invalid-feedback>
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
                        v-model="form.role"
                        :options="role_list"
                        required
                    ></b-form-select>
                    <b-form-invalid-feedback>This field is required</b-form-invalid-feedback>
                </b-form-group>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <b-button type="submit" block variant="primary" class="pad-top" :disabled="button.disable">
                    <span class="spinner-border spinner-border-sm text-danger" v-show="button.disable"></span>
                    {{button.text}}
                </b-button>
            </div>
        </div>
    </b-form>
</template>
<script>
export default {
    props: [
        'edit_user',
        'role_list',
    ],
    data() {
        return {
            validated: false,
            form: {
                username:   '',
                email:      '',
                first_name: '',
                last_name:  '',
                role:       4,
            },
            button: {
                text: 'Create New User and Send Welcome Email',
                disable: false,
            },
            error: {
                username: 'Enter A Valid Username',
                email:    'Enter A Valid Email Address',
                bool: {
                    username: null,
                    email:    null,
                }
            },
            loading: {
                username: false,
            }
        }
    },
    created() {
        console.log(this.role_list);
    },
    methods: {
        validateForm(e)
        {
            e.preventDefault();
            console.log(this.form);
            if(this.$refs.userForm.checkValidity() === false)
            {
                this.validated = true;
            }
            else
            {
                console.log('good to go');
            }
        },
        checkUsername()
        {
            if(this.form.username)
            {
                console.log(this.form.username);
                this.loading.username = true;
                axios.get(this.route('admin.checkUser', [this.form.username, 'username']))
                    .then(res => {
                        console.log(res);
                        this.loading.username = false;
                        if(res.data.duplicate)
                        {
                            this.error.username = 'This username is taken by '+res.data.user;
                            // if(res.data.active)
                            // {
                                // this.error.username.appendChild(' Note - this user has been deactivated');
                            // }
                            this.error.bool.username = false;
                        }
                        else
                        {
                            this.error.bool.username = true;
                        }
                    }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error));
            }
        },
        checkEmail()
        {
            if(this.form.email)
            {
                console.log(this.form.email);
                this.loading.email = true;
                axios.get(this.route('admin.checkUser', [this.form.email, 'email']))
                    .then(res => {
                        console.log(res);
                        this.loading.email = false;
                        if(res.data.duplicate)
                        {
                            this.error.email = 'This email address is taken by '+res.data.user;
                            // if(res.data.active)
                            // {
                                // this.error.username.appendChild(' Note - this user has been deactivated');
                            // }
                            this.error.bool.email = false;
                        }
                        else
                        {
                            this.error.bool.email = true;
                        }
                    }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error));
            }
        },
        resetForm()
        {
            this.validated       = false;
            this.button.disable  = false;
            this.button.text     = 'Create New User and Send Welcome Email';
            this.form.username   = '';
            this.form.email      = '';
            this.form.first_name = '';
            this.form.last_name  = '';
        }
    }
}
</script>
