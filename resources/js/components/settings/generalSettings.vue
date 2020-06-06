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
        <b-form @submit="validateForm" ref="settings-form" novalidate :validated="validated">
            <b-form-group label="Timezone:" label-for="timezone">
                <b-form-select v-model="form.timezone" id="timezone">
                    <optgroup v-for="(group, key) in tz_list" :key="key" :label="key">
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
            <form-submit
                button_text="Update Settings"
                :submitted="submitted"
            ></form-submit>
        </b-form>
    </b-overlay>
</template>

<script>
    export default {
        props: {
            tz_list: {
                type:     Object,
                required: true,
            },
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
                    timezone: this.settings.timezone,
                    filesize: this.settings.filesize,
                }
            }
        },
        created() {
            //
        },
        mounted() {
             //
             console.log(this.tz_list);
        },
        computed: {
             //
        },
        watch: {
             //
        },
        methods: {
            validateForm(e)
            {
                e.preventDefault();
                console.log(this.form);
                if(this.$refs['settings-form'].checkValidity() === false)
                {
                     this.validated = true;
                }
                else
                {
                     console.log('ready to go');
                     this.submitted = true;
                     axios.post(this.route('settings.submit_general'), this.form)
                         .then(res => {
                             console.log(res);
                             this.submitted = false;
                             this.$bvModal.msgBoxOk('Settings Updated');
                         }).catch(error => this.eventHub.$emit('axiosError', error));
                }
            }
        }
    }
</script>
