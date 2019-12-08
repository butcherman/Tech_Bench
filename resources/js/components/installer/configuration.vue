<template>
    <div>
        <b-form @submit="submitForm" novalidate :validated="validated" ref="configForm">
            <b-form-group label="URL:" label-for="url">
                <b-form-input
                    id="url"
                    type="url"
                    v-model="form.url"
                    required
                ></b-form-input>
                <b-form-invalid-feedback>Please enter a valid URL</b-form-invalid-feedback>
                <span class="small"><strong class="text-danger">Important Note:</strong>  Incorrect settings here could cause you to lose connectivity.</span>
            </b-form-group>
            <b-form-group label="Timezone:" label-for="timezone">
                <b-form-select v-model="form.timezone" id="timezone">
                    <optgroup v-for="(group, key) in timezones" :key="key" :label="key">
                        <option v-for="(zone, label) in group" :key="label" :value="label" v-html="zone"></option>
                    </optgroup>
                </b-form-select>
            </b-form-group>
            <b-form-group label="Max File Upload Size:" label-for="filesize">
                <b-form-input
                    id="filesize"
                    type="range"
                    v-model="form.filesize"
                    min="500"
                    max="10737418240"
                    required
                ></b-form-input>
                <b-form-invalid-feedback>This field is required</b-form-invalid-feedback>
                <span class="small">File Size - {{form.filesize | prettyBytes}}</span>
            </b-form-group>
            <b-button type="submit" variant="primary" class="pad-top" block :diable="button.disable">
                <span class="spinner-border spinner-border-sm text-danger" v-show="button.disable"></span>
                {{button.text}}
            </b-button>
        </b-form>
    </div>
</template>

<script>
export default {
    props: [
        'settings',
        'timezones',
    ],
    data() {
        return {
            validated: false,
            form: {
                url:      this.settings.url,
                timezone: this.settings.timezone,
                filesize: this.settings.filesize,
            },
            button: {
                disable: false,
                text: 'Update Settings',
            }
        }
    },
    methods: {
        submitForm(e)
        {
            e.preventDefault();
            if(this.$refs.configForm.checkValidity() === false)
            {
                this.validated = true;
            }
            else
            {
                this.button.disable = true;
                this.button.text = 'Processing...';
                axios.post(this.route('admin.submitConfig'), this.form)
                    .then(res => {
                        location.reload();
                    }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error));
            }
        }
    }
}
</script>
