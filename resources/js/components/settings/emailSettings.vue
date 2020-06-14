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
        <b-form @submit="validateForm" ref="email-settings-form" novalidate :validated="validated">
            <b-form-group label="From Email Address:" label-for="fromEmail">
                <b-form-input
                    id="fromEmail"
                    type="email"
                    v-model="form.from_address"
                    required
                    placeholder="no-reply@example.com"
                ></b-form-input>
                <b-form-invalid-feedback>Please Enter A Valid Email Address</b-form-invalid-feedback>
            </b-form-group>
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
                    :options="encryptionOptions"
                ></b-form-select>
            </b-form-group>
            <b-form-checkbox
                class="text-center"
                v-model="form.authentication"
                switch
            >
                Authentication
            </b-form-checkbox>
            <transition name="fade">
                <div v-if="form.authentication">
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
                </div>
            </transition>
            <b-button variant="warning" block @click="sendTestEmail">Send Test Email</b-button>
            <form-submit
                class="mt-3"
                button_text="Update Email Settings"
                :submitted="submitted"
            ></form-submit>
        </b-form>
    </b-overlay>
</template>

<script>
    export default {
        props: {
            settings: {
                type:     Object,
                required: true,
            }
        },
        data() {
            return {
                submitted: false,
                validated: false,
                form: {
                    from_address:   this.settings.from_address,
                    username:       this.settings.username,
                    password:       this.settings.username ? 'RandomString' : null,
                    host:           this.settings.host,
                    port:           this.settings.port,
                    encryption:     this.settings.encryption,
                    authentication: this.settings.username != null ? true : false,
                },
                encryptionOptions: [
                    {value: 'tls',  text: 'TLS'},
                    {value: 'ssl',  text: 'SSL'},
                    {value: 'none', text: 'None'},
                ],
            }
        },
        created() {
            //
        },
        mounted() {
             //
        },
        computed: {
             //
        },
        watch: {
             //
        },
        methods: {
            sendTestEmail()
            {
                if(this.$refs['email-settings-form'].checkValidity() === true)
                {
                    this.submitted = true;
                    axios.put(this.route('settings.test_email'), this.form)
                        .then(res => {
                            if(res.data.success === true)
                            {
                                this.validated = true;
                                this.$bvModal.msgBoxOk('Test Email Succeeded');
                            }
                            else
                            {
                                this.$bvModal.msgBoxOk('Test Email Failed - '+res.data.success);
                            }
                            this.submitted = false;
                        }).catch(error => this.eventHub.$emit('axiosError', error));
                }
                else
                {
                    this.validated = true;
                }
            },
            validateForm(e)
            {
                e.preventDefault();
                if(this.$refs['email-settings-form'].checkValidity() === false)
                {
                     this.validated = true;
                }
                else
                {
                    this.submitted = true;
                    axios.post(this.route('settings.submit_email'), this.form)
                        .then(res => {
                            this.$bvModal.msgBoxOk('Email Settings Updated');
                            this.submitted = false;
                            this.validated = false;
                        }).catch(error => this.eventHub.$emit('axiosError', error));
                }
            }
        }
    }
</script>
