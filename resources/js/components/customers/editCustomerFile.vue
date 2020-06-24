<template>
    <div class="d-inline">
        <i class="fas fa-pencil-alt pointer" title="Edit" v-b-tooltip @click="openModal"></i>
        <b-modal title="New Customer File" ref="customer-edit-file-modal" hide-footer centered size="lg">
            <b-form @submit="validateForm" novalidate :validated="validated" ref="customer-edit-file-form">
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
                    </fieldset>
                    <form-submit button_text="Update File" :submitted="submitted"></form-submit>
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
            },
            file_data: {
                type:     Object,
                required: true,
            }
        },
        data() {
            return {
                validated: false,
                submitted: false,
                fileTypes: [],
                form: {
                    cust_id:      this.cust_id,
                    name:         '',
                    file_type_id: null,
                    shared:       false,
                },
            }
        },
        methods: {
            openModal()
            {
                if(!this.fileTypes.length)
                {
                    this.getFileTypes();
                }
                this.form = {
                    cust_id:      this.cust_id,
                    name:         this.file_data.name,
                    file_type_id: this.file_data.customer_file_types.file_type_id,
                    shared:       this.file_data.shared,
                }
                this.$refs['customer-edit-file-modal'].show();
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
            validateForm(e)
            {
                e.preventDefault();
                if(this.$refs['customer-edit-file-form'].checkValidity() === false)
                {
                     this.validated = true;
                }
                else
                {
                    this.submitted = true;
                    axios.put(this.route('customer.files.update', this.file_data.cust_file_id), this.form)
                        .then(res => {
                            this.$refs['customer-edit-file-modal'].hide();
                            this.$emit('file-updated');
                        }).catch(error => this.eventHub.$emit('axiosError', error));
                }
            },
        }
    }
</script>
