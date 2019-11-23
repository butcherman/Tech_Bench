<template>
    <div>
        <div v-if="error">
            <h5 class="text-center">Problem Loading Data...</h5>
        </div>
        <div v-else-if="loading">
            <h5 class="text-center">Loading Files</h5>
            <img src="/img/loading.svg" alt="Loading..." class="d-block mx-auto">
        </div>
        <vue-good-table v-else
            ref="customer-files-table"
            styleClass="vgt-table bordered w-100"
            :columns="table.columns"
            :rows="table.rows"
            isLoading.sync="isLoading"
        >
            <div slot="emptystate">
                <h4 class="text-center">No Files</h4>
            </div>
            <div slot="table-actions">
                <b-button variant="info block" v-b-modal.file-form-modal><i class="ti-plus"></i> Add File</b-button>
            </div>
            <template slot="table-row" slot-scope="data">
                <span v-if="data.column.field == 'name'">
                    <a :href="route('download', [data.row.files.file_id, data.row.files.file_name])">{{data.row.name}}</a>
                </span>
                <span v-else-if="data.column.field == 'actions'">
                    <i class="ti-pencil pointer" title="Edit" v-b-tooltip @click="editFile(data.row)"></i>
                    <i class="ti-trash pointer" title="Delete" v-b-tooltip @click="deleteFile(data.row)"></i>
                </span>
            </template>
        </vue-good-table>
        <b-modal :title="modalTitle" id="file-form-modal" ref="fileFormModal" size="lg" hide-footer>
            <b-form @submit="submitFile" novalidate :validated="validated" ref="fileForm" method="post" enctype="multipart/form-data">
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
                    <b-form-select v-model="form.type" :options="file_types" required>
                        <template v-slot:first>
                            <option :value="null" disabled>Please Select An Option</option>
                        </template>
                    </b-form-select>
                    <b-form-invalid-feedback>You must select a file type</b-form-invalid-feedback>
                </b-form-group>
                <vue-dropzone id="dropzone" v-if="!edit"
                    class="filedrag"
                    ref="fileDropzone"
                    @vdropzone-total-upload-progress="updateProgressBar"
                    @vdropzone-sending="sendingFiles"
                    @vdropzone-queue-complete="queueComplete"
                    :options="dropzoneOptions">
                </vue-dropzone>
                <div v-if="fileError" class="invalid-feedback d-block">You must select a file</div>
                <b-progress v-show="showProgress" :value="progress" variant="success" striped animate show-progress></b-progress>
                <b-button type="submit" block variant="primary" class="pad-top" :disabled="button.disable">
                    <span class="spinner-border spinner-border-sm text-danger" v-show="button.disable"></span>
                    {{button.text}}
                </b-button>
            </b-form>
        </b-modal>
    </div>
</template>

<script>
    export default {
        props: [
            'cust_id',
            'file_types',
        ],
        data () {
            return {
                token: window.techBench.csrfToken,
                loading: true,
                error: false,
                fileError: false,
                isLoading: false,
                validated: false,
                edit: false,
                modalTitle: 'Add File',
                table: {
                    columns: [
                        {
                            label: 'File Name',
                            field: 'name'
                        },
                        {
                            label: 'File Type',
                            field: 'customer_file_types.description',
                        },
                        {
                            label: 'Uploaded By',
                            field: 'user.full_name',
                        },
                        {
                            label: 'Uploaded Date',
                            field: 'created_at',
                        },
                        {
                            label: 'Actions',
                            field: 'actions',
                        }
                    ],
                    rows: [],
                },
                button: {
                    disable: false,
                    text: 'Add File',
                },
                form: {
                    cust_id: this.cust_id,
                    name: '',
                    type: null,
                },
                progress: 0,
                showProgress: false,
                dropzoneOptions: {
                    url: this.route('customer.files.store'),
                    autoProcessQueue: false,
                    parallelUploads: 1,
                    maxFiles: 1,
                    maxFilesize: window.techBench.maxUpload,
                    addRemoveLinks: true,
                    chunking: true,
                    chunkSize: 5000000,
                    parallelChunkUploads: false,
                },
            }
        },
        created()
        {
            this.getFiles();
        },
        methods: {
            getFiles()
            {
                axios.get(this.route('customer.files.show', this.cust_id))
                    .then(res => {
                        this.loading = false;
                        this.table.rows = res.data;
                    }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error));
            },
            submitFile(e)
            {
                e.preventDefault();
                if(!this.edit)
                {
                    var myDrop = this.$refs.fileDropzone;

                    if(this.$refs.fileForm.checkValidity() === false || myDrop.getQueuedFiles().length != 1)
                    {
                        this.validated = true;
                        if(myDrop.getQueuedFiles().lenght != 1 && !this.edit)
                        {
                            this.fileError = true;
                        }
                    }
                    else
                    {
                        this.fileError = false;
                        this.button.diable = true;
                        this.button.text = 'Uploading File';
                        this.showProgress = true;
                        myDrop.processQueue();
                    }
                }
                else
                {
                    this.button.disable = true;
                    this.button.text = 'Processing...';
                    axios.put(this.route('customer.files.update', this.edit), this.form)
                        .then(res => {
                            this.$bvModal.hide('file-form-modal');
                            this.resetForm();
                            this.getFiles();
                        }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error));
                }
            },
            editFile(data)
            {
                this.edit = data.cust_file_id;
                this.form.name = data.name;
                this.form.type = data.customer_file_types.file_type_id;
                this.modalTitle = 'Edit File';
                this.button.text = 'Update File';
                this.$bvModal.show('file-form-modal');
            },
            deleteFile(data)
            {
                this.$bvModal.msgBoxConfirm('Please confirm you want to delete the file.', {
                    title: 'Are You Sure?',
                    size: 'md',
                    okVariant: 'danger',
                    okTitle: 'Yes',
                    cancelTitle: 'No',
                    centered: true,
                }).then(res => {
                    if(res)
                    {
                        this.loading = true;
                        axios.delete(this.route('customer.files.destroy', data.cust_file_id))
                            .then(res => {
                                this.getFiles();
                                this.resetForm();
                            }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error));
                    }
                });
            },
            updateProgressBar(progress)
            {
                this.progress = progress;
            },
            sendingFiles(file, xhr, formData)
            {
                formData.append('_token', this.token);
                formData.append('cust_id', this.form.cust_id);
                formData.append('name', this.form.name);
                formData.append('type', this.form.type);
            },
            queueComplete()
            {
                console.log('done');
                this.$refs.fileFormModal.hide();
                this.getFiles();
                this.resetForm();
            },
            resetForm()
            {
                this.form.name = '';
                this.form.type = null;
                this.button.text = 'Add File';
                this.button.disable = false;
                this.showProgress = false;
                this.edit = false;
                this.validated = false;
            }
        },
    }
</script>
