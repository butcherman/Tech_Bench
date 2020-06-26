<template>
    <div>
        <b-button variant="primary" pill size="sm" @click="openModal">
            <i class="fas fa-plus" aria-hidden="true"></i>
            Add File
        </b-button>
        <b-modal title="New Customer File" ref="customer-file-modal" id="customer-file-modal" hide-footer centered size="lg">
            <b-form @submit="validateForm" novalidate :validated="validated" ref="customer-new-file-form">
                <b-overlay :show="showOverlay">
                    <template v-slot:overlay>
                        <atom-spinner
                            :animation-duration="1000"
                            :size="60"
                            color="#ff1d5e"
                            class="mx-auto"
                        />
                        <h4 class="text-center">Processing</h4>
                    </template>
                    <fieldset :disabled="submitted">
                        <b-form-group
                            label="File Name"
                            label-for="fileName"
                        >
                            <b-form-input
                                id="fileName"
                                v-model="form.name"
                                type="text"
                                required
                                placeholder="Enter Descriptive Name"></b-form-input>
                                <b-form-invalid-feedback>You must enter a name for the file</b-form-invalid-feedback>
                        </b-form-group>
                        <b-form-group
                            label="File Type"
                            label-for="fileType"
                            >
                            <b-form-select v-model="form.file_type_id" :options="fileTypes" value-field="file_type_id" text-field="description" required>
                                <template v-slot:first>
                                    <option :value="null" disabled>Please Select A File Type</option>
                                </template>
                            </b-form-select>
                            <b-form-invalid-feedback>You must select a file type</b-form-invalid-feedback>
                        </b-form-group>
                        <b-form-checkbox v-model="form.shared" switch v-if="linked_site">File is Shared Across All Sites</b-form-checkbox>
                        <b-form-group>
                            <file-upload ref="file-upload"
                                :submit_route="route('customer.files.store')"
                                :max_files="1"
                                @file-added="populateName"
                                @upload-finished="uploadComplete"
                            ></file-upload>
                            <b-form-invalid-feedback v-show="validate.file" :class="invalidFileClass">A File Must Be Attached</b-form-invalid-feedback>
                        </b-form-group>
                    </fieldset>
                    <form-submit button_text="Add File" :submitted="submitted"></form-submit>
                </b-overlay>
            </b-form>
        </b-modal>
    </div>
</template>

<script>
    export default {
        props: {
            cust_id: {
                type:     Number,
                required: true,
            },
            linked_site: {
                type:     Boolean,
                required: false,
                default:  false,
            }
        },
        data() {
            return {
                validated: false,
                submitted: false,
                loading:   false,
                fileTypes: [],
                form: {
                    cust_id:      this.cust_id,
                    name:         '',
                    file_type_id: null,
                    shared:       false,
                },
                validate: {
                    file: false,
                }
            }
        },
        computed: {
            showOverlay()
            {
                return this.loading ? true : false;
            },
            invalidFileClass()
            {
                return this.validate.file ? 'd-inline' : null;
            }
        },
        methods: {
            openModal()
            {
                if(!this.fileTypes.length)
                {
                    this.getFileTypes();
                }
                this.$refs['customer-file-modal'].show();
            },
            getFileTypes()
            {
                this.loading = true;
                axios.get(this.route('customer.files.index'))
                    .then(res => {
                        this.fileTypes = res.data;
                        this.loading = false;
                    }).catch(error => this.eventHub.$emit('axiosError', error));
            },
            populateName(name)
            {
                if(this.form.name == '')
                {
                    this.form.name = name;
                }
            },
            validateForm(e)
            {
                e.preventDefault();
                if(this.$refs['customer-new-file-form'].checkValidity() === false || this.$refs['file-upload'].getFileCount() != 1)
                {
                     this.validated = true;
                     if(this.$refs['file-upload'].getFileCount() != 1)
                     {
                         this.validate.file = true;
                     }
                     else
                     {
                         this.validate.file = false;
                     }
                }
                else
                {
                    this.submitted = true;
                    this.$refs['file-upload'].submitFiles(this.form);
                }
            },
            uploadComplete()
            {
                this.form = {
                    cust_id:      this.cust_id,
                    name:         '',
                    file_type_id: null,
                    shared:       false,
                };
                this.$refs['customer-file-modal'].hide();
                this.$refs['file-upload'].reset();
                this.$emit('file-uploaded');
            }
        }
    }
</script>
