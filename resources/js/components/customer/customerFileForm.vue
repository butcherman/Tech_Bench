<template>
    <b-modal :title="modalTitle" hide-footer centered ref="fileModal" size="lg">
        <div v-if="error">
            <h5 class="text-center text-danger"><i class="fas fa-exclamation-circle"></i> Something bad happened...</h5>
        </div>
        <div v-else-if="loading">
            <atom-spinner
                :animation-duration="1000"
                :size="60"
                color="#ff1d5e"
                class="mx-auto"
            />
            <h4 class="text-center">Loading Form</h4>
        </div>
        <b-overlay v-else :show="submitted">
            <template v-slot:overlay>
                <atom-spinner
                    :animation-duration="1000"
                    :size="60"
                    color="#ff1d5e"
                    class="mx-auto"
                />
                <h4 class="text-center">Processing...</h4>
            </template>
            <b-form @submit="validateForm" novalidate :validated="validated" ref="fileForm">
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
                    <b-form-select v-model="form.customer_file_types.file_type_id" :options="fileTypes" required>
                        <template v-slot:first>
                            <option :value="null" disabled>Please Select An Option</option>
                        </template>
                    </b-form-select>
                    <b-form-invalid-feedback>You must select a file type</b-form-invalid-feedback>
                </b-form-group>
                <file-upload ref="fileUpload"
                    :submit_url="route('customer.files.store')"
                    :max_files="1"
                    @uploadFinished="uploadFinished">
                </file-upload>
                <b-form-invalid-feedback v-show="validatedFileErr">A File Must Be Attached</b-form-invalid-feedback>
                <form-submit
                    class="mt-3"
                    :button_text="btnText"
                    :submitted="submitted"
                ></form-submit>
            </b-form>
        </b-overlay>
    </b-modal>
</template>

<script>
export default {
    props: {
        //
        cust_id: {
            type: Number,
            required: true,
        },
    },
    data() {
        return {
            error: false,
            loading: false,
            submitted: false,
            validated: false,
            validatedFileErr: false,
            newFile: false,
            fileTypes: [],
            form: {
                cust_id: this.cust_id,
                name: '',
                shared: false,
                customer_file_types: {
                    file_type_id: null,
                },
            },
        }
    },
    computed: {
        modalTitle()
        {
            return this.newFile ? 'Upload New File' : 'Edit File';
        },
        btnText()
        {
            return this.newFile ? 'Upload File' : 'Update File';
        }
    },
    methods: {
        initNewFile()
        {
            this.getFileTypes();
            this.newFile = true;
            this.form.name = '';
            this.form.shared = false;
            this.form.cust_id = this.cust_id;
            this.form.customer_file_types.file_type_id = null;
            this.$refs['fileModal'].show();
        },
        initEditFile(file)
        {
            console.log(file);
            this.getFileTypes();
            this.newFile = false;
            this.form = file;
            this.form.cust_id = this.cust_id;
            this.$refs['fileModal'].show();
        },
        getFileTypes()
        {
            this.loading = true;
            axios.get(this.route('customer.files.index'))
                .then(res => {
                    this.fileTypes = res.data;
                    this.loading = false;
                });
        },
        validateForm(e)
        {
            e.preventDefault();
            if(this.$refs.fileForm.checkValidity() === false)
            {
                this.validated = true;
            }
            else
            {
                this.submitted = true;
                if(this.newFile)
                {
                    this.form.file_type_id = this.form.customer_file_types.file_type_id;
                    var fileZone = this.$refs.fileUpload;
                    if(fileZone.getFileCount() != 1)
                    {
                        this.validated = true;
                        this.validatedFileErr = true;
                        this.submitted = false;
                    }
                    else
                    {
                        this.validatedFileErr = false;
                        fileZone.submitFiles(this.form);
                    }
                }
                else
                {
                    axios.put(this.route('customer.files.update', this.form.cust_file_id), this.form)
                        .then(res => {
                            this.$emit('completed');
                            this.$refs['fileModal'].hide();
                            this.submitted = false;
                        }).catch(error => this.$bvModal.msgBoxOk('Something bad happened.  Please try again later.'));
                }
            }
        },
        uploadFinished()
        {
            this.$emit('completed');
            this.$refs['fileModal'].hide();
            this.submitted = false;
        }
    }
}
</script>
