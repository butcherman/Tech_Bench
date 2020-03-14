<template>
    <div>
        <b-alert :show="alert.display" dismissible :variant="alert.variant"><h4 class="text-center">{{alert.message}}</h4></b-alert>
        <img v-if="!loadDone" src="/img/loading.svg" alt="Loading..." class="d-block mx-auto">
        <div v-else-if="error">
            <h5 class="text-center">Problem Loading Data...</h5>
        </div>
        <div v-else class="table">
            <vue-good-table
                mode="remote"
                ref="files-table"
                styleClass="vgt-table bordered w-100"
                :columns="table.columns"
                :rows="table.rows"
                :select-options="{enabled:true, selectOnCheckboxOnly: true}"
                :sort-options="{enabled:true}"
                isLoading.sync="loadDone"
            >
                <div slot="emptystate">
                    <h4 class="text-center">No Files</h4>
                </div>
                <div slot="table-actions-bottom">
                    <div class="row justify-content-center">
                        <div class="col-md-4">
                            <button class="btn btn-block btn-primary" v-b-modal.new-file-modal>Add File</button>
                        </div>
                    </div>
                </div>
                <div slot="selected-row-actions">
                    <div class="row justify-content-center">
                        <div class="col-md-3">
                            <button id="delete-selected" class="btn btn-warning btn-block">Delete Selected Files</button>
                            <b-popover :target="'delete-selected'" triggers="focus" placement="bottom">
                                <template v-slot:title><strong class="d-block text-center">Are You Sure?</strong>This cannot be undone.</template>
                                <div class="text-center">
                                    <button class="btn btn-danger" @click="deleteChecked">Yes</button>
                                    <button class="btn btn-primary" @click="$root.$emit('bv::hide::popover')">No</button>
                                </div>
                            </b-popover>
                        </div>
                        <div class="col-md-3">
                            <button id="download-selected" class="btn btn-primary btn-block" @click="downloadChecked">Download Selected Files</button>
                        </div>
                    </div>
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
                            <span :id="'details-'+data.row.files.file_id" class="fas fa-comment-dots pointer text-danger"></span>
                            <b-popover :target="'details-'+data.row.files.file_id" triggers="click" placement="left" title="File Details">
                                <pre>{{data.row.note}}</pre>
                            </b-popover>
                        </div>
                    </span>
                    <span v-else-if="data.column.field == 'actions'">
                        <div class="d-flex flex-nowrap">
                            <button v-if="cust_id" class="btn btn-rounded px-0 text-muted mr-2" title="Place File In Customer Files" @click="moveFile = data.row.files" v-b-tooltip.hover v-b-modal.file-type>
                                <span class="fas fa-file-export"></span>
                            </button>
                            <button :id="'confirm-delete-'+data.row.files.file_id" class="btn btn-rounded px-0 text-muted" title="Delete File" v-b-tooltip.hover>
                                <span class="fas fa-trash-alt"></span>
                            </button>
                            <b-popover :target="'confirm-delete-'+data.row.files.file_id" triggers="focus" placement="left">
                                <template v-slot:title><strong class="d-block text-center">Are You Sure?</strong>This cannot be undone.</template>
                                <div class="text-center">
                                    <button class="btn btn-danger" @click="deleteFile(data.row.link_file_id)">Yes</button>
                                    <button class="btn btn-primary" @click="$root.$emit('bv::hide::popover')">No</button>
                                </div>
                            </b-popover>
                        </div>
                    </span>
                </template>
            </vue-good-table>
        </div>
        <b-modal id="file-type" title="Select File Type" ref="fileTypeModal" hide-footer>
            <b-list-group>
                <b-list-group-item v-for="type in file_types" v-bind:key="type.value" @click="setFileType(type)" button>
                    {{type.text}}
                </b-list-group-item>
            </b-list-group>
        </b-modal>
        <b-modal id="new-file-modal" title="Add File" ref="newFileModal" hide-footer>
            <b-form @submit="addFile" method="post" enctype="multipart/form-data" novalidate>
                <vue-dropzone id="dropzone"
                    class="filedrag"
                    ref="fileDropzone"
                    @vdropzone-upload-progress="updateProgressBar"
                    @vdropzone-sending="sendingFiles"
                    @vdropzone-queue-complete="queueComplete"
                    :options="dropzoneOptions">
                </vue-dropzone>
                <b-progress v-show="showProgress" :value="progress" variant="success" striped animate show-progress></b-progress>
                <b-button type="submit" block variant="primary" :disabled="button.disable">{{button.text}}</b-button>
            </b-form>
        </b-modal>
    </div>
</template>

<script>
    export default {
        props: [
            'link_id',
            'cust_id',
            'file_types'
        ],
        data() {
            return {
                token: window.techBench.csrfToken,
                loadDone: false,
                error: false,
                alert: {
                    variant: 'danger',
                    display: false,
                    message: 'Something Bad Happened',
                },
                moveFile: [],
                table: {
                    columns: [
                        {
                            label: 'File Name',
                            field: 'name',
                            filterable: true,
                        },
                        {
                            label: 'Date Added',
                            field: 'created_at',
                            filterable: true,
                        },
                        {
                            label: 'Added By',
                            field: 'user',
                            filterable: true,
                        },
                        {
                            label: 'File Notes',
                            field: 'details',
                            filterable: false,
                        },
                        {
                            label: 'Actions',
                            field: 'actions',
                            filterable: false,
                        }
                    ],
                    rows: []
                },
                dropzoneOptions: {
                    url: this.route('links.files.store'),
                    autoProcessQueue: false,
                    parallelUploads: 1,
                    maxFiles: 5,
                    maxFilesize: window.techBench.maxUpload,
                    addRemoveLinks: true,
                    chunking: true,
                    chunkSize: window.techBench.chunkSize,
                    parallelChunkUploads: false,
                },
                button: {
                    disable: false,
                    text: 'Submit File',
                },
                progress: 0,
                showProgress: false,
            }
        },
        created() {
            this.getFiles();
        },
        methods: {
            //  Get the files attached to the link
            getFiles()
            {
                axios.get(this.route('links.files.show', this.link_id))
                    .then(res => {
                        //  remove the loading screen and populate the table
                        this.loadDone = true;
                        this.table.rows = res.data;
                    }).catch(error => { this.error = true; });
            },
            //  When moving a file to a customer file list, select the appropriate file type tag and move the file
            setFileType(type)
            {
                //  Get the basic file information prepared to move
                var moveData = {
                    fileID: this.moveFile.file_id,
                    fileName: this.moveFile.file_name,
                    fileType: type.value,
                };
                console.log(moveData);
                //  Move the file
                axios.put(this.route('links.files.update', this.link_id), moveData)
                    .then(res => {
                        //  Close the Modal and dispaly alert with reason for success or failure
                        this.$refs.fileTypeModal.hide();
                        if(res.data.success == true)
                        {
                            this.alert.display = true;
                            this.alert.variant = 'success';
                            this.alert.message = 'File has been moved to customer files and can be safely deleted from this link.';
                        }
                        else
                        {
                            this.alert.display = true;
                            this.alert.variant = 'danger';
                            this.alert.message = res.data.reason;
                        }
                    }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error));
            },
            downloadChecked()
            {
                //  place all of the files in an array to be sent for zipping
                var fileList = [];
                this.$refs['files-table'].selectedRows.forEach(function(file)
                {
                    fileList.push(file.file_id);
                });

                //  prepare the zip file for download
                axios.put(this.route('downloadAll'), {'fileList': fileList})
                    .then(res => {
                    }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error));
            },
            //  Add a new file to the link
            addFile(e)
            {
                e.preventDefault();
                //  Make sure there is a file in place, and process it
                var myDrop = this.$refs.fileDropzone;
                if(myDrop.getQueuedFiles().length > 0)
                {
                    this.button.text = 'Loading...';
                    this.button.disable = true;
                    this.showProgress = true;
                    myDrop.processQueue();
                }
                else
                {
                    this.$refs['newFileModal'].hide();
                }
            },
            //  Delete a group o ffiles
            deleteChecked()
            {
                var obj = this;
                this.$refs['files-table'].selectedRows.forEach(function(file)
                {
                    obj.deleteFile(file.link_file_id);
                });
            },
            //  Delete a single file (Note - this will only delete the file from this link.  If it is attached to a customer, the file itself will not be deleted)
            deleteFile(fileid)
            {
                axios.delete(this.route('links.files.destroy', fileid))
                    .then(res => {
                        //  Reload the file list
                        this.getFiles();
                        this.$root.$emit('bv::hide::popover')
                    }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error));
            },
            updateProgressBar(file, progress, sent)
            {
                var fileProgress = 100 - (file.size / sent * 100);
                this.progress = Math.round(fileProgress);
            },
            //  As each chunk is sent, include the token to validate request
            sendingFiles(file, xhr, formData)
            {
                formData.append('_token', this.token);
                formData.append('linkID', this.link_id);
            },
            //  Finalize the action, and reload the files
            queueComplete()
            {
                this.getFiles();
                this.button.disable = false;
                this.button.text = 'Submit File';
                this.progress = 0;
                this.showProgress = false;
                this.$refs['newFileModal'].hide();
            },
        }
    }
</script>
