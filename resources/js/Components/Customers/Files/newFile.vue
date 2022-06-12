<template>
    <b-button class="float-right" pill variant="primary" size="sm" v-b-modal.new-file-modal>
        <i class="fas fa-plus"></i>
        Add
        <b-modal
            id="new-file-modal"
            ref="new-file-modal"
            title="Add New File"
            hide-footer
            @hide="checkForUpload"
            @hidden="resetForm"
        >
            <b-overlay :show="loading" no-center>
                <template #overlay>
                    <progress-bar v-if="uploading" :percent-done="fileProgress" />
                    <form-loader v-else />
                </template>
                <ValidationObserver v-slot="{handleSubmit}">
                    <b-form @submit.prevent="handleSubmit(submitForm)" novalidate>
                        <text-input
                            label="Name"
                            name="name"
                            v-model="form.name"
                            rules="required"
                            placeholder="Enter a Descriptive Name"
                        />
                        <dropdown-input
                            label="File Type"
                            name="type"
                            v-model="form.type"
                            rules="required"
                            :options="file_types"
                            value-field="description"
                            text-field="description"
                            placeholder="What Type of File Is This?"
                        />
                        <b-form-checkbox v-show="allow_share" v-model="form.shared" class="text-center" switch>Share File Across All Sites</b-form-checkbox>
                        <dropzone-upload
                            ref="dropzone-upload"
                            disk="customers"
                            :folder="cust_id"
                            @file-added="checkName"
                            @upload-canceled="canceled"
                            @upload-progress="updateProgressbar"
                            @completed="uploadDone"
                            @validation-error="canceled"
                        />
                        <submit-button button_text="Upload File" :submitted="submitted"/>
                    </b-form>
                </ValidationObserver>
            </b-overlay>
        </b-modal>
    </b-button>
</template>

<script>
    export default {
        props: {
            cust_id: {
                type:     Number,
                required: true,
            },
            allow_share: {
                type:     Boolean,
                default:  false,
            }
        },
        data() {
            return {
                loading:      false,
                submitted:    false,
                uploading:    false,
                fileProgress: 0,
                form: {
                    cust_id: this.cust_id,
                    name:    '',
                    type:    null,
                    shared:  false,
                },
            }
        },
        computed: {
            file_types() {
                return this.$page.props.file_types;
            }
        },
        methods: {
            submitForm()
            {
                this.submitted = true;
                this.loading   = true;
                this.uploading = true;
                this.$refs['dropzone-upload'].process();
            },
            resetForm()
            {
                this.form = {
                    cust_id: this.cust_id,
                    name:    '',
                    type:    null,
                    shared:  false,
                };
                this.fileProgress = 0;
            },
            //  If the name field is empty, we will populate it with the name of the file
            checkName(name)
            {
                if(this.form.name === '' || this.form.name === null)
                {
                    this.form.name = name;
                }
            },
            //  If a file was canceled during upload, go back to form
            canceled()
            {
                this.submitted = false;
                this.loading   = false;
            },
            //  Update the overlay's progress bar
            updateProgressbar(progress)
            {
                this.fileProgress = progress;
            },
            //  File upload is completed
            uploadDone()
            {
                this.uploading = false;
                this.$inertia.post(route('customers.files.store'), this.form, {
                    onFinish: () =>
                    {
                        this.loading   = false;
                        this.submitted = false;
                        this.$refs['new-file-modal'].hide();
                        this.resetForm();
                    }
                });
            },
            checkForUpload(e)
            {
                if(this.submitted)
                {
                    alert('File Upload In Progress.  Please wait until completed, or cancel upload');
                }
            }
        },
    }
</script>
