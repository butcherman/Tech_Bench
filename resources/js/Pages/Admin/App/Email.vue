<template>
    <div>
        <div class="row">
            <div class="col-12 grid-margin">
                <h4 class="text-center text-md-left">Email Settings</h4>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <ValidationObserver v-slot="{handleSubmit}">
                            <b-overlay :show="submitted">
                                <template #overlay>
                                    <form-loader text="Processing..."></form-loader>
                                </template>
                                <b-form @submit.prevent="handleSubmit(submitForm)" novalidate>
                                    <text-input type="email" rules="required|email" label="From Email Address" v-model="form.from_address" placeholder="no-reply@example.com"></text-input>
                                    <text-input type="text" rules="required" label="Host" v-model="form.host" placeholder="smtp.example.com"></text-input>
                                    <text-input type="number" rules="required" label="Port" v-model="form.port" placeholder="25"></text-input>
                                    <dropdown-input ruled="required" label="Encryption Type" v-model="form.encryption" :options="encryptionOptions"></dropdown-input>
                                    <b-form-checkbox
                                        class="text-center"
                                        v-model="form.authentication"
                                        switch
                                    >
                                        Authentication
                                    </b-form-checkbox>
                                    <transition name="fade">
                                        <div v-if="form.authentication">
                                            <text-input type="text" rules="required" label="Email Username" v-model="form.username" placeholder="Enter Username"></text-input>
                                            <text-input type="password" rules="required" label="Email Password" v-model="form.password" placeholder="Enter Password"></text-input>
                                        </div>
                                    </transition>
                                    <submit-button button_text="Save Email Settings" :submitted="submitted" class="mt-3" />
                                </b-form>
                            </b-overlay>
                        </ValidationObserver>
                        <b-button variant="warning" block @click="sendTestEmail" class="mt-3" :disabled="sending">
                            <span v-if="sending">
                                <span class="spinner-border spinner-border-sm text-danger"></span>
                                Sending....
                            </span>
                            <span v-else>Send Test Email</span>
                        </b-button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import App from '../../../Layouts/app';

    export default {
        layout: App,
        props: {
            /**
             * Current Email Settings
             */
            settings: {
                type:     Object,
                required: true,
            }
        },
        data() {
            return {
                submitted: false,
                sending:   false,
                form: this.$inertia.form({
                    from_address:   this.settings.from_address,
                    username:       this.settings.username,
                    password:       this.settings.username ? 'RandomString' : null,
                    host:           this.settings.host,
                    port:           this.settings.port,
                    encryption:     this.settings.encryption,
                    authentication: this.settings.username != null ? true : false,
                }),
                encryptionOptions: [
                    {value: 'tls',  text: 'TLS'},
                    {value: 'ssl',  text: 'SSL'},
                    {value: 'none', text: 'None'},
                ],
            }
        },
        methods: {
            submitForm()
            {
                this.submitted = true;

                this.form.post(route('admin.set-email'), {
                    onFinish: ()=> {
                        this.submitted = false;
                    }
                });
            },
            sendTestEmail()
            {
                if(this.form.isDirty)
                {
                    this.$bvModal.msgBoxOk('Email Settings have changed.  Please save before sending test email')
                }
                else
                {
                    this.sending = true;
                    axios.get(route('admin.test-email'))
                        .then(res => {
                            this.sending = false;

                            if(res.data.success)
                            {
                                this.$bvModal.msgBoxOk('Test Email Sent');
                            }
                            else
                            {
                                this.$bvModal.msgBoxOk('Response from server - '+res.data.message)
                            }
                        }).catch(error => this.eventHub.$emit('axiosError', error));
                }
            }
        }
    }
</script>
