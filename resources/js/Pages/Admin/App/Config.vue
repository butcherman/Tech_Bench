<template>
    <div>
        <div class="row">
            <div class="col-12 grid-margin">
                <h4 class="text-center text-md-left">Configuration</h4>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <ValidationObserver v-slot="{handleSubmit}">
                            <b-overlay :show="submitted">
                                <template #overlay>
                                    <form-loader text="Processing..."></form-loader>
                                </template>
                                <b-form @submit.prevent="handleSubmit(submitForm)" novalidate>
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
                                    <text-input v-model="form.url" name="url" label="Site URL" @change="showUrlWarning"></text-input>
                                    <submit-button button_text="Save Settings" :submitted="submitted" class="mt-3" />
                                </b-form>
                            </b-overlay>
                        </ValidationObserver>
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
             * List of all available time zones
             */
            tz_list: {
                type:     Object,
                required: true,
            },
            /**
             * Current application settings
             */
            settings: {
                type:     Object,
                required: true,
            }
        },
        data() {
            return {
                submitted: false,
                form: this.$inertia.form({
                    timezone:       this.settings.timezone,
                    filesize:       this.settings.filesize,
                    url:            this.settings.url,
                }),
            }
        },
        methods: {
            submitForm()
            {
                this.submitted = true;
                this.form.post(route('admin.set-config'), {
                    onFinish: ()=> {
                        this.submitted = false;
                    }
                });
            },
            showUrlWarning()
            {
                this.$bvModal.msgBoxOk('WARNING:  Improper Site URL configuration could cause the applicaion to fail.  Please verify settings before saving');
            }
        }
    }
</script>
