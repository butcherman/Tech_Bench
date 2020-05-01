<template>
    <div class="card">
        <div class="card-header">
            Link Files:
            <b-button pill variant="primary" size="sm" class="float-right" v-b-modal.add-file-modal>
                <i class="fas fa-plus"></i>
                Add File
            </b-button>
        </div>
        <div class="card-body">
            <div v-if="error">
                <h5 class="text-center text-danger"><i class="fas fa-exclamation-circle"></i> Unable to load Files...</h5>
            </div>
            <div v-else-if="loading">
                <atom-spinner
                    :animation-duration="1000"
                    :size="60"
                    color="#ff1d5e"
                    class="mx-auto"
                />
            </div>
            <vue-good-table
                v-else
                ref="files-table"
                styleClass="vgt-table bordered w-100"
                :columns="table.columns"
                :rows="table.rows"
                :sort-options="{enabled:true}"
                :select-options="{enabled:true, selectOnCheckboxOnly: true}"
            >
                <div slot="emptystate">
                    <h4 v-if="!loading" class="text-center">No Files</h4>
                    <atom-spinner v-else
                        :animation-duration="1000"
                        :size="60"
                        color="#ff1d5e"
                        class="mx-auto"
                    />
                </div>
                <div slot="selected-row-actions">
                    <b-button @click="downloadChecked" variant="warning" size="sm" pill>
                        <span class="spinner-border spinner-border-sm text-danger" v-show="downloadButton.disable"></span>
                        {{downloadButton.text}}
                    </b-button>
                    <b-button @click="deleteChecked" variant="danger" size="sm" pill>Delete Selected</b-button>
                </div>
                <template slot="table-row" slot-scope="data">
                    <span v-if="data.column.field == 'name'">
                        <a :href="route('download', [data.row.files.file_id, data.row.files.file_name])">{{data.row.files.file_name}}</a>
                    </span>
                    <span v-else-if="data.column.field == 'user'">
                        <span v-if="data.row.added_by">{{data.row.added_by}}</span>
                        <span v-else>{{data.row.user.full_name}}</span>
                    </span>
                    <span v-else-if="data.column.field === 'details'">
                        <div v-if="data.row.note">
                            <i class="fas fa-comment-dots pointer text-danger" @click="openNoteModal(data.row.note)" title="Click to read attached note" v-b-tooltip.hover></i>
                        </div>
                    </span>
                    <span v-else-if="data.column.field == 'actions'">
                        <div class="d-flex flex-nowrap">
                            <i v-if="cust_id" @click="prepMoveFile(data.row.files)" class="fas fa-file-export pointer ml-2" title="Move file to customer's files" v-b-tooltip.hover></i>
                            <i @click="confirmDelete(data.row)" class="fas fa-trash-alt pointer ml-2" title="Delete File" v-b-tooltip.hover></i>
                        </div>
                    </span>
                </template>
            </vue-good-table>
        </div>
        <b-modal id="add-file-modal" title="Add File" ref="addFileModal" hide-footer>
            <b-form @submit="submitFile">
                <file-upload
                    ref="fileUpload"
                    :submit_url="route('links.files.store')"
                    @uploadFinished="uploadFinished">
                </file-upload>
                <form-submit
                    button_text="Add File"
                    :submitted="submitted"
                ></form-submit>
            </b-form>
        </b-modal>
        <b-modal id="file-type" title="Select File Type" ref="fileTypeModal" hide-footer>
            <b-list-group>
                <b-list-group-item v-for="type in fileTypes" v-bind:key="type.value" @click="moveFile(type)" button>
                    {{type.text}}
                </b-list-group-item>
            </b-list-group>
        </b-modal>
    </div>
</template>

<script>
    export default {
        props: {
            link_id: {
                type:     Number,
                required: true,
            },
            cust_id: {
                type:     Number,
                required: false,
                default:  null,
            }
        },
        data() {
            return {
                error:      false,
                loading:    true,
                submitted:  false,
                movingFile: null,
                fileTypes:  [],
                downloadButton: {
                    text:   'Download Selected',
                    disable: false,
                },
                table: {
                    columns: [
                        {
                            label:     'File Name',
                            field:     'name',
                            filterable: true,
                        },
                        {
                            label:     'Date Added',
                            field:     'created_at',
                            filterable: true,
                        },
                        {
                            label:     'Added By',
                            field:     'user',
                            filterable: true,
                        },
                        {
                            label:     'File Notes',
                            field:     'details',
                            filterable: false,
                        },
                        {
                            label:     'Actions',
                            field:     'actions',
                            filterable: false,
                        }
                    ],
                    rows: []
                },
            }
        },
        mounted() {
             this.getFiles();
        },
        watch: {
             cust_id()
             {
                 this.getFiles();
             }
        },
        methods: {
            //  Get the files attached to the link
            getFiles()
            {
                this.isLoading = true;
                axios.get(this.route('links.files.show', this.link_id))
                    .then(res => {
                        this.loading = false;
                        this.table.rows = res.data;
                    }).catch(error => { this.error = true; });
            },
            downloadChecked()
            {
                this.downloadButton.text    = 'Processing..';
                this.downloadButton.disable = true;

                //  place all of the files in an array to be sent for zipping
                var fileList = [];
                this.$refs['files-table'].selectedRows.forEach(function(file)
                {
                    fileList.push(file.file_id);
                });

                //  prepare the zip file for download
                axios.put(this.route('archiveFiles'), {'fileList': fileList})
                    .then(res => {
                        window.location.href        = this.route('downloadArchive', res.data.archive);
                        this.downloadButton.text    = 'Download Selected';
                        this.downloadButton.disable = false;
                    }).catch(error => this.$bvModal.msgBoxOk('Download Checked operation failed.  Please try again later.'));
            },
            deleteChecked()
            {
                this.$bvModal.msgBoxConfirm('This cannot be undone.', {
                    title:       'Are You Sure?',
                    size:        'md',
                    okVariant:   'danger',
                    okTitle:     'Yes',
                    cancelTitle: 'No',
                    centered:     true,
                })
                .then(res => {
                    if(res)
                    {
                        var obj      = this;
                        this.loading = true;
                        this.$refs['files-table'].selectedRows.forEach(function(file)
                        {
                            obj.deleteFile(file);
                        });
                        this.getFiles();
                    }
                });
            },
            //  Verify to delete a file
            confirmDelete(file)
            {
                this.$bvModal.msgBoxConfirm('This cannot be undone.', {
                    title:       'Are You Sure?',
                    size:        'md',
                    okVariant:   'danger',
                    okTitle:     'Yes',
                    cancelTitle: 'No',
                    centered:     true,
                })
                .then(res => {
                    if(res)
                    {
                        this.isLoading = true;
                        this.deleteFile(file);
                    }
                });
            },
            //  Delete individual file
            deleteFile(file)
            {
                axios.delete(this.route('links.files.destroy', file.link_file_id))
                    .then(res => {
                        this.getFiles();
                    }).catch(error => this.bvModal.msgBoxOk('Something bad happened.  Please try again later.'));
            },
            //  Submit a new file
            submitFile(e)
            {
                e.preventDefault();
                this.submitted = true;
                this.$refs.fileUpload.submitFiles({linkID: this.link_id});
            },
            //  Reload after file has been uploaded
            uploadFinished()
            {
                this.submitted = false;
                this.$refs['addFileModal'].hide();
                this.getFiles();
            },
            //  Read a note attached to the file
            openNoteModal(note)
            {
                this.$bvModal.msgBoxOk(note, {
                    title:      'File Notes',
                    size:       'lg',
                    buttonSize: 'sm',
                    centered:    true
                });
            },
            //  Determine which file type should be associated to the file in the customer's file list
            prepMoveFile(file)
            {
                if(!this.fileTypes.length)
                {
                    axios.get(this.route('customer.files.index'))
                        .then(res => {
                            this.fileTypes = res.data;
                        }).catch(error => this.bvModal.msgBoxOk('Something bad happened.  Please try again later.'));
                }
                this.$refs.fileTypeModal.show();
                this.movingFile = file;
            },
            //  Complete the moving of the file
            moveFile(type)
            {
                //  Get the basic file information prepared to move
                var moveData = {
                    fileID:   this.movingFile.file_id,
                    fileName: this.movingFile.file_name,
                    fileType: type.value,
                };
                //  Move the file
                axios.put(this.route('links.files.update', this.link_id), moveData)
                    .then(res => {
                        //  Close the Modal and dispaly alert with reason for success or failure
                        var msg = 'Unable to move the file at this time.  Please try again later';
                        this.$refs.fileTypeModal.hide();
                        if(res.data.success == true)
                        {
                            msg = 'File has been moved to customer files and can be safely deleted from this link.';
                        }
                        else
                        {
                            msg = 'This File Already Exists in Customer Files';
                        }
                        this.$bvModal.msgBoxOk(msg);
                    }).catch(error => {this.$bvModal.msgBoxOk('Move file operation failed.  Please try again later.')});
            },
        }
    }
</script>
