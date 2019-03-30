<template>
    <b-form @submit="submitForm">
        <b-form-group label="Email Host" label-for="host">
            <b-form-input id="host" type="text" v-model="settings.host" placeholder="https://smtp.server.example"></b-form-input>
        </b-form-group>
        <b-form-group label="Email Port" label-for="port">
            <b-form-input id="port" type="text" v-model="settings.port" placeholder="25"></b-form-input>
        </b-form-group>
        <b-form-select :options="options" v-model="settings.encryption"></b-form-select>
        <b-form-group label="Email Username" label-for="username">
            <b-form-input id="username" type="text" v-model="settings.username" placeholder="Enter Username"></b-form-input>
        </b-form-group>
        <b-form-group label="Email Password" label-for="password">
            <b-form-input id="password" type="password" v-model="settings.password" placeholder="Enter Password"></b-form-input>
        </b-form-group>
        <b-button :variant="btnVariant" block class="pad-bottom" @click="testEmail" v-html="testStatus"></b-button>
        <b-button type="submit" block variant="info">Update Email Settings</b-button>
    </b-form>
</template>

<script>
export default {
    props: [
        'submit_route',
        'current_settings',
    ],
    data() {
        return {
            options: [
                {value: 'tls', text: 'TLS'},
                {value: 'ssl', text: 'SSL'},
                {value: 'none', text: 'None'},
            ],
            btnVariant: 'warning',
            settings: {
                host:       this.current_settings.host,
                port:       this.current_settings.port,
                encryption: this.current_settings.encryption,
                username:   this.current_settings.username,
                password:   this.current_settings.password,
            },
            testStatus: 'Send Test Email',
        }
    },
    methods: {
        testEmail()
        {
            this.testStatus = 'Sending... <i class="fa fa-spinner fa-spin"></i>',
            axios.put(this.submit_route, this.settings)
                .then(res => {
                    console.log(res);
                })
                .catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error))
        },
        submitForm(e)
        {
            e.preventDefault();
            console.log('submitted');
            console.log(this.current_settings);
            console.log(this.current_settings.host);
            console.log(this.settings.host);
        }
    }
}
</script>
