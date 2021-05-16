<template>
    <b-button class="float-right" pill variant="primary" size="sm" v-b-modal.new-file-modal>
        <i class="fas fa-plus"></i>
        New
        <b-modal
            id="new-file-modal"
            ref="new-file-modal"
            title="Add New File"
            hide-footer
            @hidden="resetForm"
        >
            <b-overlay :show="loading" no-center>
                <template #overlay>
                    <progress-bar :percent-done="fileProgress"></progress-bar>
                </template>
                <ValidationObserver v-slot="{handleSubmit}">
                    <b-form @submit.prevent="handleSubmit(submitForm)" novalidate>
                        <text-input
                            label="Name"
                            name="name"
                            v-model="form.name"
                            rules="required"
                            placeholder="Enter a Descriptive Name"
                        ></text-input>
                        <dropdown-input
                            label="File Type"
                            name="type"
                            v-model="form.type"
                            rules="required"
                            :options="file_types"
                            value-field="description"
                            text-field="description"
                            placeholder="What Type of File Is This?"
                        ></dropdown-input>
                        <dropzone-upload
                            ref="dropzone-upload"
                            :url="route('customers.files.store')"
                            @file-added="checkName"
                            @upload-canceled="canceled"
                            @upload-progress="updateProgressbar"
                            @completed="uploadDone"
                            @validation-error="canceled"
                        ></dropzone-upload>
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <b-form-checkbox v-model="form.shared" switch>Share File Across All Sites</b-form-checkbox>
                            </div>
                        </div>
                        <submit-button button_text="Upload File" :submitted="submitted"></submit-button>
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
                type: Number,
                required: true,
            }
        },
        data() {
            return {
                loading:   false,
                submitted: false,
                fileProgress: 0,
                form: {
                    cust_id: this.cust_id,
                    name:   '',
                    type:   null,
                    shared: false,
                },
            }
        },
        computed: {
            file_types() {
                return this.$page.props.file_types;
            }
        },
        methods: {
            //  Upload the file and file information
             submitForm()
            {
                this.submitted = true;
                this.loading   = true;
                this.$refs['dropzone-upload'].process(this.form);
            },
            //  Reset the form and dropzone to its original state
            resetForm()
            {
                this.form = {
                    cust_id: this.cust_id,
                    name:   '',
                    type:   null,
                    shared: false,
                };
                this.fileProgress = 0;
                this.submitted    = false;
                this.loading      = false;
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
                this.$emit('upload-completed');
                this.$refs['new-file-modal'].hide();
                this.resetForm();
            }
        },
    }
</script>
