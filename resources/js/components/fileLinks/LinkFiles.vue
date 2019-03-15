<template>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th><input type="checkbox" class="check-all-links" v-model="selectAll" @click="select" /></th>
                    <th>File Name:</th>
                    <th>Date Added:</th>
                    <th>Actions:</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-center">
                        <b-button variant="info" v-if="file_direction === 'down'" v-b-modal.addFileModal>Add File</b-button>
                        <b-button variant="info" v-if="file_direction === 'up'" @click=downloadChecked>Download Checked</b-button>
                        <click-confirm class="d-inline">
                            <b-button variant="info" @click="deleteChecked">{{button.text}}</b-button>
                        </click-confirm>
                    </td>
                </tr>
            </tfoot>
            <tbody>
                <tr v-for="file in fileList">
                    <td><input type="checkbox" class="check-link" :value="file.file_id" v-model="selected" /></td>
                    <td><b-link :href=file.url>{{file.file_name}}</b-link></td>
                    <td>{{file.timestamp}}</td>
                    <td>
                        <i class="fa fa-share pointer" v-b-tooltip.hover title="Move File To Customer Files" @click="moveFile"  v-if="has_customer" :data-id="file.file_id"></i>
                        <click-confirm class="d-inline">
                            <i class="fa fa-trash pointer" v-b-tooltip.hover title="Delete File"  :data-id="file.file_id" @click="deleteFile"></i>
                        </click-confirm>
                    </td>
                </tr>
                <tr v-if="fileList.length == 0">
                    <td colspan="4" class="text-center"><h4>No Files</h4></td>
                </tr>
            </tbody>
        </table>
        <b-modal id="move-file-modal" title="Select Type This File Should Be Stored As" ref="moveFileModal" hide-footer centered>
            <b-form @submit="processSelect">
                <b-form-group label="Name For File:" label-for="file_name">
                    <b-form-input
                        id="file_name"
                        type="text"
                        v-model="file_name"
                        required
                    />
                </b-form-group>
                <b-form-select v-model="typeSelect" required name="selected">
                    <option :value="null" disabled>-- Please select an option --</option>
                    <option v-for="type in fileTypes" :value="type.file_type_id">{{type.description}}</option>
                </b-form-select>
                <b-button type="submit" block variant="info" class="mt-2">Submit</b-button>
            </b-form>
        </b-modal>
        <b-modal id="addFileModal" title="Add File" ref="addFileModal" hide-footer centered>
            <b-form @submit="submitFile" method="post" enctype="multipart/form-data">
                <vue-dropzone  
                    id="dropzone"
                    class="filedrag"
                    ref="myVueDropzone" 
                    v-on:vdropzone-sending-multiple="sendingFiles"
                    v-on:vdropzone-success-multiple="completedUpload"
                    :options="dropzoneOptions">
                </vue-dropzone>
                <b-button type="submit" block variant="primary" :disabled="button.dis">{{button.addText}}</b-button>
            </b-form>
        </b-modal>
    </div>
</template>

<script>
    export default {
        props: [
            'file_direction',
            'files_route',
            'has_customer',
            'get_file_types_route',
            'del_file_route',
            'dl_all_route',
        ],
        data() {
            return {
                dropzoneOptions: {
                    url: this.files_route,
                    autoProcessQueue: false,
                    uploadMultiple: true,
                    parallelUploads: 10,
                    maxFiles: 10,
                    maxFilesize: window.techBench.maxUpload,
                    addRemoveLinks: true,
                },
                withSelected: [
                    { value: 'download', text: 'Download'},
                    { value: 'delete', text: 'Delete'} ],
                fileList: [],
                file: '',
                file_id: '',
                file_name: '',
                fileTypes: [],
                type: '',
                typeSelect: null,
                selected: [],
                selectAll: false,
                button: {
                    text: 'Delete Checked',
                    addText: 'Add File',
                    dis: false
                },
                token: window.techBench.csrfToken,
            }
        },
        created() {
            this.getFiles();
            this.getFileTypes();
        },
        methods: {
            getFiles()
            {
                axios.get(this.files_route)
                    .then(res => {
                        this.fileList = res.data;
                    })
                    .catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error))
            },
            getFileTypes()
            {
                axios.get(this.get_file_types_route)
                    .then(res => {
                        this.fileTypes = res.data;
                    })
                    .catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error))
            },
            //  Activate the check all box
            select()
            {
                this.selected = [];
                if(!this.selectAll)
                {
                    for(let i in this.fileList)
                    {
                        this.selected.push(this.fileList[i].file_id);
                    }
                }
            },
            moveFile(e)
            {
                this.file_id = e.currentTarget.dataset.id,
                this.$refs.moveFileModal.show();
            },
            processSelect(e)
            {
                e.preventDefault();
                axios.put(this.files_route, {
                        selected: this.typeSelect,
                        file_id: this.file_id,
                        file_name: this.file_name,
                    })
                    .then(res => {
                        this.$refs.moveFileModal.hide();
                        if(res.data.success === 'duplicate')
                        {
                            alert('The Customer Already Has This File');
                        }
                        else
                        {
                            alert('File Successfully Moved');
                        }
                    })
                    .catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error))
            },
            //  Delete a single file
            deleteFile(e)
            {
                var url = this.del_file_route.replace(':file', e.currentTarget.dataset.id);
                axios.delete(url)
                    .then(res => {
                        this.getFiles();
                    })
                    .catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error))
            },
            //  Delete multiple links
            deleteChecked()
            {
                var obj = this;
                
                if(obj.selected.length != 0)
                {
                    obj.button.text = 'Loading...';
                    obj.button.dis  = true;
                    this.selected.forEach(function(item)
                    {
                        var url = obj.del_file_route.replace(':file', item);
                        axios.delete(url)
                            .then(res => {
                                obj.getFiles();
                                obj.button.text = 'Delete Checked';
                            })
                            .catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error))
                    });
                }
            },
            submitFile(e)
            {
                e.preventDefault();
                var myDrop = this.$refs.myVueDropzone; //.processQueue();
                var myForm = new FormData(document.querySelector("form"));
                
                this.button.addText = 'Loading...';
                this.button.dis = true;
                
                myDrop.processQueue();                
            },
            sendingFiles(file, xhr, formData)
            {
                formData.append('_token', this.token);
            },
            completedUpload(file, res)
            {
                this.getFiles();
                this.$refs.addFileModal.hide();
                this.$refs.myVueDropzone.removeAllFiles();
            },
            downloadChecked()
            {
                var dFiles = [];
                
                if(this.selected.length != 0)
                {
                    this.selected.forEach(function(item)
                    {
                        dFiles.push(item);
                    });

                    axios.put(this.dl_all_route, {
                        fileArr: dFiles
                    })
                    .then(res => {
                        window.location.href = this.dl_all_route;
                    })
                    .catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error));
                }
            }
        }
    }
</script>
