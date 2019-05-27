<template>
    <div>
        <vue-good-table
            ref="files-table"
            :columns="table.cols"
            :rows="table.rows"
            :select-options="{enabled: true, selectOnCheckboxOnly: true}"
            :sort-options="{enabled: true}"
        >
            <div slot="emptystate">
                <h3 class="text-center">No Files</h3>
            </div>
            <template slot="table-row" slot-scope="data">
                <span v-if="data.column.field == 'name'">
                    <a :href="download_route.replace(':fileID', data.row.file_id).replace(':filename', data.row.file_name)">{{data.row.file_name}}</a>
                </span>
                <span v-else-if="data.column.field == 'user'">
                    <span v-if="data.row.upload == true">{{data.row.added_by}}</span>
                    <span v-else>Downloadable File</span>
                </span>
                <span v-else-if="data.column.field == 'actions'">
                    <i class="fa fa-share pointer" v-if="has_customer" v-b-tooltip.hover title="Move File To Customer Files" @click="moveFile(data.row)"></i>
                    <click-confirm class="d-inline">
                        <i class="fa fa-trash pointer" v-b-tooltip.hover title="Delete File" @click="deleteFile(data.row.file_id)"></i>
                    </click-confirm>
                </span>
            </template>
            <div slot="selected-row-actions">
                <click-confirm class="d-inline">
                    <button class="btn btn-info" @click="deleteChecked">Delete Selected</button>
                </click-confirm>
                <button class="btn btn-info" @click="downloadChecked">Download Selected</button>
            </div>
            <div slot="table-actions-bottom">
                <div class="col text-center m-2">
                    <button class="btn btn-info text-center" v-b-modal.addFileModal>Add File</button>
                </div>
            </div>
        </vue-good-table>
        <b-modal id="move-file-modal" title="Select Type This File Should Be Stored As" ref="moveFileModal" hide-footer centered>
            <b-form @submit="moveCustomerFile">
                <b-form-group label="Name For File:" label-for="file_name">
                    <b-form-input
                        id="file_name"
                        type="text"
                        v-model="moveForm.fileName"
                        required
                    />
                </b-form-group>
                <b-form-select v-model="moveForm.fileType" required name="selected">
                    <option :value="null" disabled>-- Please Select A File Type --</option>
                    <option v-for="type in JSON.parse(customer_file_types)" :value="type.file_type_id">{{type.description}}</option>
                </b-form-select>
                <b-button type="submit" block variant="info" class="mt-2">Submit</b-button>
            </b-form>
        </b-modal>
        <b-modal id="addFileModal" title="Add File" ref="addFileModal" hide-footer centered>
            <b-form @submit="submitNewFile">
                <vue-dropzone  
                    id="dropzone"
                    class="filedrag"
                    ref="myVueDropzone" 
                    v-on:vdropzone-total-upload-progress="updateProgressBar"
                    v-on:vdropzone-sending="sendingFiles"
                    v-on:vdropzone-queue-complete="completedUpload"
                    :options="dropzoneOptions">
                </vue-dropzone>
                <b-progress v-show="showProgress" :value="progress" variant="success" striped animate show-progress></b-progress>
                <b-button type="submit" block variant="primary" :disabled="addButton.dis">{{addButton.text}}</b-button>
            </b-form>
        </b-modal>
    </div>
</template>

<script>
    export default {
        props: [
            'link_id',
            'files_route',
            'has_customer',
            'download_route',
            'customer_file_types',
            'download_all_route',
        ],
        data() {
            return {
                token: window.techBench.csrfToken,
                table: {
                    cols: [
                        {
                            label: 'File Name',
                            field: 'name',
                        },
                        {
                            label: 'Date Added',
                            field: 'timestamp',
                        },
                        {
                            label: 'Added By',
                            field: 'user',
                        },
                        {
                            label: 'File Notes',
                            field: 'note',
                        },
                        {
                            label: 'Actions',
                            field: 'actions',
                        }
                    ],
                    rows: [],
                },
                moveForm: {
                    fileID:   '',
                    fileName: '',
                    fileType: 'null',
                },
                fileTypes: [],
                addButton: {
                    dis: false,
                    text: 'Add File',
                },
                dropzoneOptions: {
                    url: this.files_route,
                    autoProcessQueue: false,
                    parallelUploads: 1,
                    maxFiles: 5,
                    maxFilesize: window.techBench.maxUpload,
                    addRemoveLinks: true,
                    chunking: true,
                    chunkSize: 5000000,
                    parallelChunkUploads: false,
                },
                progress: 0,
                showProgress: false,
            }
        },
        created() {
            this.getFiles();
        },
        methods: {
            getFiles()
            {
                axios.get(this.files_route+'/'+this.link_id)
                    .then(res => {
                        this.table.rows = res.data;
                    }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error))
            },
            moveFile(data)
            {
                this.moveForm.fileID   = data.file_id;
                this.moveForm.fileName = data.file_name;
                this.$refs.moveFileModal.show();
            },
            moveCustomerFile(e)
            {
                e.preventDefault();
                console.log(this.moveForm);
                
                axios.put(this.files_route+'/'+this.link_id, this.moveForm)
                    .then(res => {
                        if(res.data.success === 'duplicate')
                        {
                            alert('The Customer Already Has This File');
                        }
                        
                        this.$refs.moveFileModal.hide();
                    }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error))
            },
            //  Delete a single file
            deleteFile(id)
            {
                axios.delete(this.files_route+'/'+id)
                    .then(res => {
                        this.getFiles();
                    })
                    .catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error))
            },
            deleteChecked()
            {
                var obj = this;
                this.$refs['files-table'].selectedRows.forEach(function(file)
                {
                    obj.deleteFile(file.file_id);
                });
            },
            downloadChecked()
            {
                var dFiles = [];
                
                this.$refs['files-table'].selectedRows.forEach(function(file)
                {
                    dFiles.push(file.file_id);
                });
                
                axios.put(this.download_all_route, {fileArr: dFiles})
                    .then(res => {
                        window.location.href = this.download_all_route;
                    }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error));
            },
            submitNewFile(e)
            {
                e.preventDefault();
                
                var myDrop = this.$refs.myVueDropzone;
                var MyForm = new FormData();
                
                this.addButton.text = 'Loading...';
                this.addButton.dis = true;
                
                myDrop.processQueue();
            },
            sendingFiles(file, xhr, formData)
            {
                formData.append('_token', this.token);
                formData.append('linkID', this.link_id);
            },
            updateProgressBar(progress)
            {
                this.progress = progress;
            },
            completedUpload(file, res)
            {
                console.log(res);
                
                this.getFiles();
                this.$refs.addFileModal.hide();
                this.$refs.myVueDropzone.removeAllFiles();
            },
        }
    }
</script>
