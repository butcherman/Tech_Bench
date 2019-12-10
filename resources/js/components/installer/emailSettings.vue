<template>
    <div>
        <b-alert :show="alert.show" :variant="alert.variant"><h3 class="text-center">{{alert.message}}</h3></b-alert>
        <div class="row justify-content-center">
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <b-form @submit="submitForm" ref="emailSettingsForm" novalidate :validated="validated">
                            <b-form-group label="Host:" label-for="host">
                                <b-form-input
                                    id="host"
                                    type="text"
                                    v-model="form.host"
                                    required
                                    placeholder="smtp.example.com"
                                ></b-form-input>
                                <b-form-invalid-feedback>Please Enter A Valid SMTP Host</b-form-invalid-feedback>
                            </b-form-group>
                            <b-form-group label="Port:" label-for="port">
                                <b-form-input
                                    id="port"
                                    type="number"
                                    v-model="form.port"
                                    required
                                    placeholder="25"
                                ></b-form-input>
                                <b-form-invalid-feedback>Please Enter A Valid TCP Port</b-form-invalid-feedback>
                            </b-form-group>
                            <b-form-group label="Encryption Type:" label-for="encryption">
                                <b-form-select
                                    id="encryption"
                                    name="encryption"
                                    v-model="form.encryption"
                                    :options="portOptions"
                                ></b-form-select>
                                <b-form-invalid-feedback>Please Enter A Valid TCP Port</b-form-invalid-feedback>
                            </b-form-group>
                            <b-form-group label="Email Username" label-for="username">
                                <b-form-input
                                    id="username"
                                    type="text"
                                    name="username"
                                    v-model="form.username"
                                    placeholder="Enter Username"
                                ></b-form-input>
                                <b-form-invalid-feedback>Please Enter A Valid Username</b-form-invalid-feedback>
                            </b-form-group>
                            <b-form-group label="Email Password" label-for="password">
                                <b-form-input
                                    id="password"
                                    type="password"
                                    name="password"
                                    v-model="form.password"
                                    placeholder="Enter Password"
                                ></b-form-input>
                                <b-form-invalid-feedback>Please Enter A Valid Password</b-form-invalid-feedback>
                            </b-form-group>
                            <b-form-group label="From Name:" label-for="fromName">
                                <b-form-input
                                    id="fromName"
                                    type="text"
                                    v-model="form.fromName"
                                    required
                                    placeholder="Tech Bench"
                                ></b-form-input>
                                <b-form-invalid-feedback>Please Enter A From Email Address</b-form-invalid-feedback>
                            </b-form-group>
                            <b-form-group label="From Email Address:" label-for="fromEmail">
                                <b-form-input
                                    id="fromEmail"
                                    type="email"
                                    v-model="form.fromEmail"
                                    required
                                    placeholder="no-reply@example.com"
                                ></b-form-input>
                                <b-form-invalid-feedback>Please Enter A Valid Email Address</b-form-invalid-feedback>
                            </b-form-group>
                            <div class="row justify-content-center">
                                <div class="col-md-6">
                                    <b-button type="submit" block variant="primary" :disabled="button.disable">
                                        <span class="spinner-border spinner-border-sm text-danger" v-show="button.disable"></span>
                                        {{button.text}}
                                    </b-button>
                                </div>
                                <div class="col-md-6">
                                    <b-button block variant="warning" :disabled="test.disable" @click="sendTestEmail">
                                        <span class="spinner-border spinner-border-sm text-primary" v-show="test.disable"></span>
                                        {{test.text}}
                                    </b-button>
                                </div>
                            </div>
                        </b-form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: [
        'settings',
    ],
    data() {
        return {
            validated: false,
            form: {
                host:       this.settings.host,
                port:       this.settings.port,
                encryption: this.settings.encryption,
                username:   this.settings.username,
                password:   'NULL',
                fromEmail:  this.settings.fromEmail,
                fromName:   this.settings.fromName,
            },
            button: {
                disabled: false,
                text: 'Update Email Settings'
            },
            test: {
                disabled: false,
                text: 'Send Test Email'
            },
            portOptions: [
                {value: 'tls',  text: 'TLS'},
                {value: 'ssl',  text: 'SSL'},
                {value: 'none', text: 'None'},
            ],
            alert: {
                variant: 'success',
                message: 'inspirational words',
                show:    false,
            }
        }
    },
    methods: {
        submitForm(e)
        {
            e.preventDefault();
            if(this.$refs.emailSettingsForm.checkValidity() === false)
            {
                this.validated = true;
            }
            else
            {
                this.button.disable = true;
                this.button.text = 'Processing...';
                axios.post(this.route('admin.emailSettings'), this.form)
                    .then(res => {
                        if(res.data.success)
                        {
                            this.alert.variant = 'info';
                            this.alert.message = 'Email Settings Updated';
                            this.alert.show = true;
                            this.button.disable = false;
                            this.button.text = 'Update Email Settings';
                        }
                    }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error));
            }
        },
        sendTestEmail()
        {
            if(this.$refs.emailSettingsForm.checkValidity() === false)
            {
                this.validated = true;
            }
            else
            {
                this.test.disable = true;
                this.test.text = 'Sending Test Email...';
                axios.put(this.route('admin.emailSettings'), this.form)
                    .then(res => {
                        if(res.data.success)
                        {
                            this.alert.variant = 'success';
                            this.alert.message = res.data.message;
                        }
                        else
                        {
                            this.alert.variant = 'danger';
                            this.alert.message = res.data.message;
                        }
                        console.log(res);
                        this.alert.show = true;
                        this.test.disable = false;
                        this.test.text = 'Send Test Email';
                    }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error));
            }
        }
    }
}
</script>
