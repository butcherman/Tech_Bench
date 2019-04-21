<template>
    <div>
      <b-card no-body>
        <b-tabs card>
            <b-tab v-for="(files, type) in fileList" :key="type" :title="files.description">
                <b-card-text>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr class="text-center">
                                <th>File</th>
                                <th>Added By</th>
                                <th>Date Added</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-center"><b-button variant="info" @click="newFileForm(files.description)">Add File</b-button></td>
                            </tr>
                        </tfoot>
                        <tbody>
                            <tr v-if="files.data === false">
                                <td colspan="4" class="text-center"><h4>No Files</h4></td>
                            </tr> 
                            <tr v-for="file in files.data" class="text-center">
                                <td>
                                    <a :href="file.url" :id="file.name.replace(' ', '-')">
                                        {{file.name}}
                                        <b-popover v-if="file.description" :target="file.name.replace(' ', '-')" title="File Description" triggers="hover">{{file.description}}</b-popover>
                                    </a>
                                </td>
                                <td>{{file.user}}</td>
                                <td>{{file.added}}</td>
                                <td>
                                    <i class="fa fa-pencil text-muted pointer" v-b-tooltip.hover title="Edit File" @click="editFormOpen(files.description, file)"></i> 
                                    <i class="fa fa-retweet text-muted pointer pl-2" v-b-tooltip.hover title="Replace File" @click="replaceFormOpen(file)"></i> 
                                    <click-confirm class="d-inline">
                                        <i class="fa fa-trash text-muted pointer pl-2" v-b-tooltip.hover title="Delete File" @click="deleteFile(file)"></i> 
                                    </click-confirm>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </b-card-text>
            </b-tab>
        </b-tabs>
      </b-card>
      <b-modal id="new-file-modal" ref="new-file-modal" title="New File" hide-footer centered>
          <b-form @submit="newFile">
              <input type="hidden" name="sysID" v-model="newForm.sysID" />
              <b-form-group label="Type:" label-for="new-file-type">
                  <b-form-input id="new-file-type" type="text" v-model="newForm.type" disabled></b-form-input>
              </b-form-group>
              <b-form-group label="Name:" label-for="new-name">
                  <b-form-input id="new-name" type="text" v-model="newForm.name" required></b-form-input>
              </b-form-group>
              <b-form-group label="Description:" label-for="new-description">
                  <b-form-textarea id="new-description" v-model="newForm.desc" rows="10"></b-form-textarea>
              </b-form-group>
              <vue-dropzone  
                    id="newFileDropzone"
                    class="filedrag"
                    ref="newFileDropzone" 
                    :options="dropzoneOptions"
                    v-on:vdropzone-sending="sendingFile"
                    v-on:vdropzone-success="completedUplaod">
                </vue-dropzone>
                <b-button type="submit" block variant="primary" :disabled="newForm.buttonDis">{{newForm.buttonTxt}}</b-button>
          </b-form>
      </b-modal>
      <b-modal id="edit-modal" ref="edit-modal" title="Edit File Information" hide-footer centered>
          <b-form @submit="editFile">
              <b-form-group label="Type:" label-for="edit-file-type">
                  <b-form-input id="edit-file-type" type="text" v-model="editForm.type" disabled></b-form-input>
              </b-form-group>
              <b-form-group label="Name:" label-for="edit-name">
                  <b-form-input id="edit-name" type="text" v-model="editForm.name" required></b-form-input>
              </b-form-group>
              <b-form-group label="Description:" label-for="edit-description">
                  <b-form-textarea id="edit-description" v-model="editForm.desc" rows="10"></b-form-textarea>
              </b-form-group>
              <b-button type="submit" block variant="primary" :disabled="editForm.buttonDis">{{editForm.buttonTxt}}</b-button>
          </b-form>
      </b-modal>
      <b-modal id="replace-modal" ref="replace-modal" title="Replace File" hide-footer centered>
          <b-form @submit="replaceFile">
              <vue-dropzone  
                    id="replaceFileDropzone"
                    class="filedrag"
                    ref="replaceFileDropzone" 
                    :options="dropzoneReplaceOptions"
                    v-on:vdropzone-sending="sendingReplaceFile"
                    v-on:vdropzone-success="completedReplaceUplaod">
                </vue-dropzone>
                <b-button type="submit" block variant="primary" :disabled="replaceForm.buttonDis">{{replaceForm.buttonTxt}}</b-button>
          </b-form>
      </b-modal>
    </div>
</template>

<script>
export default {
    props: [
        'file_route',
        'new_file_route',
        'edit_file_route',
        'replace_file_route',
        'delete_route',
        'sys_id',
    ],
    data() {
        return {
            token: window.techBench.csrfToken,
            dropzoneOptions: {
                url:                  this.new_file_route,
                autoProcessQueue:     false,
                maxFilesize:          window.techBench.maxUpload,
                addRemoveLinks:       true,
                maxFiles:             1,
                chunking:             true,
                chunkSize:            5000000,
                parallelChunkUploads: false,
            },
            dropzoneReplaceOptions: {
                url:                  this.replace_file_route,
                autoProcessQueue:     false,
                uploadMultiple:       false,
                maxFilesize:          window.techBench.maxUpload,
                addRemoveLinks:       true,
                maxFiles:             1,
                chunking:             true,
                chunkSize:            5000000,
                parallelChunkUploads: false,
            },
            fileList: [],
            newForm: {
                sysID:     '',
                type:      '',
                name:      '',
                desc:      '',
                buttonDis: false,
                buttonTxt: 'Submit New File',
            },
            editForm: {
                fileID:    '',
                type:      '',
                name:      '',
                desc:      '',
                buttonDis: false,
                buttonTxt: 'Submit Edit',
            },
            replaceForm: {
                fileID:    '',
                buttonDis: false,
                buttonTxt: 'Submit Replacement File',
            }
        }
    },
    created() {
        this.getFiles();
    },
    methods: {
        getFiles()
        {
            axios.get(this.file_route)
                .then(res => {
                    this.fileList = res.data;
                })
                .catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error));
        },
        newFileForm(type)
        {
            this.newForm.sysID = this.sys_id;
            this.newForm.type  = type;
            this.$refs['new-file-modal'].show();
        },
        newFile(e)
        {
            e.preventDefault();
            var myDrop             = this.$refs.newFileDropzone;
            this.newForm.buttonDis = true;
            this.newForm.buttonTxt = 'Loading...';

            myDrop.processQueue();
        },
        sendingFile(file, xhr, formData)
        {
            formData.append('_token', this.token);
            formData.append('sysID', this.newForm.sysID);
            formData.append('type', this.newForm.type);
            formData.append('name', this.newForm.name);
            formData.append('description', this.newForm.desc);
        },
        completedUplaod(file, res)
        {
            if(res.success == true)
            {
                this.getFiles();
            }
            else
            {
                alert('There was a problem processing your request.');
            }
            
            this.$refs['new-file-modal'].hide();
            this.newForm.sysID     = '';
            this.newForm.type      = '';
            this.newForm.name      = '';
            this.newForm.desc      = '';
            this.newForm.buttonDis = false;
            this.newForm.buttonTxt = 'Submit New File';
            this.$refs.newFileDropzone.removeAllFiles();
        },
        editFormOpen(type, file)
        {
            this.$refs['edit-modal'].show();
            
            this.editForm.type   = type;
            this.editForm.name   = file.name;
            this.editForm.desc   = file.description;
            this.editForm.fileID = file.id;
        },
        editFile(e)
        {
            e.preventDefault();
            
            this.editForm.buttonDis = true;
            this.editForm.buttonTxt = 'Loading...';
            
            axios.put(this.edit_file_route.replace(':id', this.editForm.fileID), this.editForm)
                .then(res => {                
                    if(res.data.success == true)
                    {
                        this.getFiles();
                        this.editForm.fileID    = '';
                        this.editForm.type      = '';
                        this.editForm.name      = '';
                        this.editForm.desc      = '';
                        this.editForm.buttonDis = false;
                        this.editForm.buttonTxt = 'Submit Edit';
                    }
                    else
                    {
                        alert('There was a problem processing your request.');
                    }
                        
                    this.$refs['edit-modal'].hide();
                })
                .catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error));
        },
        replaceFormOpen(file)
        {
            this.$refs['replace-modal'].show();
            this.replaceForm.fileID = file.id;
        },
        replaceFile(e)
        {
            e.preventDefault();
            var myDrop             = this.$refs.replaceFileDropzone;
            this.replaceForm.buttonDis = true;
            this.replaceForm.buttonTxt = 'Loading...';

            myDrop.processQueue();
        },
        sendingReplaceFile(file, xhr, formData)
        {
            formData.append('_token', this.token);
            formData.append('fileID', this.replaceForm.fileID);
        },
        completedReplaceUplaod(file, res)
        {
            if(res.success == true)
            {
                this.getFiles();
            }
            else
            {
                alert('There was a problem processing your request.');
            }
            
            this.$refs['replace-modal'].hide();
            this.replaceForm.butonDis  = false;
            this.replaceForm.buttonTxt = 'Submit Replacement File';
            this.$refs.replaceFileDropzone.removeAllFiles();
        },
        deleteFile(file)
        {
            axios.delete(this.delete_route.replace(':id', file.id))
                .then(res => {
                    if(res.data.success == true)
                    {
                        this.getFiles();
                    }
                    else
                    {
                        alert('There was a problem processing your request.');
                    }
                })
                .catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+error));
        }
    }
}
</script>
