<template>
    <b-button
        size="sm"
        variant="primary"
        class="float-right"
        pill
        v-b-modal.new-file-modal
    >
        <i class="fas fa-plus" />
        New
        <b-modal
            id="new-file-modal"
            ref="new-file-modal"
            title="Add New File"
            size="lg"
            hide-footer
            @hide="checkForUpload"
            @hidden="form.reset()"
        >
            <b-overlay :show="submitted" no-center>
                <template #overlay>
                    <progress-bar
                        v-if="uploading"
                        :percent-done="fileProgress"
                    />
                    <form-loader v-else />
                </template>
                <ValidationObserver v-slot="{handleSubmit}">
                    <b-form
                        @submit.prevent="handleSubmit(submitForm)"
                        novalidate
                    >
                        <text-input
                            v-model="form.name"
                            name="name"
                            label="Name"
                            rules="required"
                            placeholder="Enter a Descriptive Name"
                        />
                        <dropdown-input
                            v-model="form.type"
                            :options="file_types"
                            name="type"
                            rules="required"
                            label="File Type"
                            value-field="description"
                            text-field="description"
                            placeholder="What Type of File Is This?"
                        />
                        <b-form-checkbox
                            v-model="form.shared"
                            v-if="customerStore.allowShare"
                            class="text-center"
                            switch
                        >
                            Share File Across All Sites
                        </b-form-checkbox>
                        <dropzone-upload
                            ref="dropzone-upload"
                            disk="customers"
                            :folder="customerStore.cust_id"
                            @file-added="checkName"
                            @upload-canceled="canceled"
                            @upload-progress="updateProgressbar"
                            @completed="uploadDone"
                            @validation-error="canceled"
                        />
                        <submit-button
                            button_text="Upload File"
                            :submitted="submitted"
                        />
                    </b-form>
                </ValidationObserver>
            </b-overlay>
        </b-modal>
    </b-button>
</template>

<script>
    import { useCustomerStore } from '../../../../Stores/customerStore';
    import { mapStores }        from 'pinia';

    export default {
        data() {
            return {
                submitted: false,
                uploading: false,
                fileProgress: 0,
                form     : this.$inertia.form({
                    cust_id: null,
                    name   : '',
                    type   : null,
                    shared : false,
                }),
            }
        },
        computed: {
            ...mapStores(useCustomerStore),
            file_types() {
                return this.$page.props.file_types;
            }
        },
        methods: {
            submitForm()
            {
                this.form.cust_id = this.customerStore.cust_id;
                this.submitted    = true;
                this.uploading    = true;

                this.$refs['dropzone-upload'].process();
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
                this.form.post(route('customers.files.store'), {
                    only     : ['files', 'flash', 'errors'],
                    onFinish : ()      => this.submitted = false,
                    onSuccess: ()      => {
                        this.submitted = false;
                        this.$refs['new-file-modal'].hide();
                    },
                    onError  : (error) => this.eventHub.$emit('validation-error', error),
                });
            },
            /**
             * If someone tries to navigate away from the upload modal, we need to check it is not
             * in the proces sof uploading a file before continuing
             */
            checkForUpload(e)
            {
                if(this.submitted)
                {
                    e.preventDefault();
                    alert('File Upload In Progress.  Please wait until completed, or cancel upload');
                }
            }
        },
    }
</script>
