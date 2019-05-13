<template>
    <div>
        <b-table striped :fields="columns" :items="files" show-empty>
            <template slot="name" slot-scope="data">
                <a :href="download_route.replace(':id', data.item.file_id).replace(':name', data.item.file_name)">{{data.item.name}}</a>
            </template>
            <template slot="added_by" slot-scope="data">
                {{data.item.first_name}} {{data.item.last_name}}
            </template>
            <template slot="actions" slot-scope="data">
                <click-confirm class="d-inline">
                    <i class="fa fa-trash pointer" v-b-tooltip.hover title="Delete File" @click="removeFile(data.item.cust_file_id)"></i> 
                </click-confirm>
            </template>
            <template slot="empty">
                <h3 class="text-center">No Files</h3>
            </template>
        </b-table>
        <div class="row justify-content-center pad-top">
            <div class="col-md-4">
                <b-button variant="info" block v-b-modal.new-file-modal>Add File</b-button>
            </div>
        </div>
        <b-modal id="new-file-modal" ref="newFileModal" title="Add New File" hide-footer>
            <b-form @submit="submitFile" id="new-file-form"  method="post" enctype="multipart/form-data">
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
                </b-form-group>
                <b-form-group
                   label="File Type"
                   label-for="fileType"      
                >
                   <b-form-select v-model="form.type" :options="JSON.parse(file_types)" required>
                       <option :value="null">Please Select An Option</option>
                   </b-form-select>
                </b-form-group>
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
                <b-button type="submit" block variant="primary">Add File</b-button>
            </b-form>
        </b-modal>
    </div>
</template>

<script>
    export default {
        props: [
            'cust_id',
            'file_types',
            'file_route',
            'download_route',
        ],
        data () {
            return {
                token: window.techBench.csrfToken,
                dropzoneOptions: {
                    url: this.file_route,
                    autoProcessQueue: false,
                    parallelUploads: 1,
                    maxFiles: 1,
                    maxFilesize: window.techBench.maxUpload,
                    addRemoveLinks: true,
                    chunking: true,
                    chunkSize: 5000000,
                    parallelChunkUploads: false,
                },
                columns: [
                    {
                        label: 'File Name',
                        key: 'name',
                    },
                    {
                        label: 'File Type',
                        key: 'type',
                    },
                    {
                        label: 'Uploaded By',
                        key: 'added_by',
                    },
                    {
                        label: 'Uploaded On',
                        key: 'added_on',
                        formatter: 'formatTime',
                    },
                    {
                        label: 'Actions',
                        key: 'actions',
                    }
                ],
                files: [],
                form: {
                    custID: this.cust_id,
                    name: '',
                    type: null,
                },
                progress: 0,
                showProgress: false,
            }
        },
        created() 
        {
            this.getFiles();
        },
        methods: {
            getFiles()
            {
                axios.get(this.file_route+'/'+this.cust_id)
                    .then(res => {
                        this.files = res.data;
//                        console.log(res.data);
//                        console.log(moment(data.item.added_on).format('MMM Do, YYYY'));
                    }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error));
            },
            submitFile(e)
            {
                e.preventDefault();
                
                var myDrop = this.$refs.myVueDropzone;
                
                myDrop.processQueue();
            },
            updateProgressBar(progress)
            {
                this.progress = progress;
            },
            sendingFiles(file, xhr, formData)
            {
                formData.append('_token', this.token);
                formData.append('custID', this.form.custID);
                formData.append('name', this.form.name);
                formData.append('type', this.form.type);
            },
            completedUpload(file, res)
            {
                console.log(res);
                this.$refs.newFileModal.hide();
                this.getFiles();
            },
            formatTime(str)
            {
                return moment(str).format('MMM Do, YYYY');
            },
            removeFile(id)
            {
                axios.delete(this.file_route+'/'+id)
                    .then(res => {
                        console.log(res);
                        this.getFiles();
                    }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error));
            },
        },
    }
</script>
