<template>
    <div>
        <b-form @submit="validateForm" :action="route('tips.store')" method="post" enctype="multipart/form-data" ref="newTipForm" novalidate :validated="validated">
            <b-form-group label="Subject:" label-for="subject">
                <b-form-input
                    id="subject"
                    v-model="form.subject"
                    type="text"
                    required
                    placeholder="Enter A Descriptive Subject"
                ></b-form-input>
                <b-form-invalid-feedback>Please Enter A Descriptive Subject</b-form-invalid-feedback>
            </b-form-group>
            <b-form-group label="Tip Type:" label-for="tip-type">
                <b-form-select
                    v-model="form.tipType"
                    :options="tip_types"
                    required
                >
                    <template v-slot:first>
                        <option :value="null" disabled>Please Select An Option</option>
                    </template>
                </b-form-select>
                <b-form-invalid-feedback>Please Select A Tip Type</b-form-invalid-feedback>
            </b-form-group>
            <b-form-group label="Equipment Types:" label-for="equipment">
                <multiselect
                    v-model="form.equipment"
                    :options="sys_types"
                    :multiple="true"
                    group-values="system_types"
                    group-label="name"
                    label="name"
                    track-by="sys_id"
                    :allow-empty="false"
                    :group-select="true"
                    placeholder="Select A System Type"
                ></multiselect>
                <b-form-invalid-feedback class="d-block" v-if="validEquip">You Must Select At Least One Equipment Type</b-form-invalid-feedback>
            </b-form-group>
            <b-form-group label="Tip Details:" label-for="tip">
                <editor :init="tinymce" v-model="form.tip"></editor>
                <b-form-invalid-feedback class="d-block" v-if="validTip">What is a Tech Tip without an actual Tip?</b-form-invalid-feedback>
            </b-form-group>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <b-form-group label="Attached Files" label-for="files">
                        <b-list-group id="files">
                            <b-list-group-item v-if="(form.activeFileList.length < 1)">No Files</b-list-group-item>
                            <b-list-group-item v-for="(file, index) in form.activeFileList" :key="file.tip_file_id" class="d-flex justify-content-between align-items-center">
                                {{file.files.file_name}}
                                <b-badge variant="danger" pill class="pointer" title="Remove File" v-b-tooltip:hover @click="delFile(index, file.tip_file_id)"><i class="fas fa-trash-alt"></i></b-badge>
                            </b-list-group-item>
                        </b-list-group>
                    </b-form-group>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <h4 class="text-center pad-top">Attach File</h4>
                    <vue-dropzone
                        id="dropzone"
                        class="filedrag"
                        ref="fileDropzone"
                        v-on:vdropzone-total-upload-progress="updateProgressBar"
                        v-on:vdropzone-sending="sendingFiles"
                        v-on:vdropzone-queue-complete="queueComplete"
                        :options="dropzoneOptions">
                    </vue-dropzone>
                </div>
            </div>
            <b-progress v-show="showProgress" :value="progress" variant="success" striped animate show-progress></b-progress>

            <b-button type="submit" block variant="primary" :disabled="button.dis">
                <span class="spinner-border spinner-border-sm text-danger" v-show="button.dis"></span>
                {{button.text}}
            </b-button>
        </b-form>
    </div>
</template>

<script>
    export default {
        props: [
            'tip_types',
            'sys_types',
            'tip_data',
            'tip_files',
        ],
        data () {
            return {
                validated: false,
                validEquip: false,
                validTip: false,
                attachFile: false,
                form: {
                    token: window.techBench.csrfToken,
                    subject: this.tip_data.subject,
                    tip: this.tip_data.description,
                    tipType: this.tip_data.tech_tip_types.tip_type_id,
                    equipment: this.tip_data.system_types,
                    supressEmail: false,
                    activeFileList: this.tip_files,
                    deletedFileList: [],
                },
                button: {
                    text: 'Update Tech Tip',
                    dis: false,
                },
                tinymce: {
                    plugins: 'autolink advlist lists link image table',
                    height: 500,
                    browser_spellcheck: true,
                    toolbar: 'formatselect | bold italic strikethrough forecolor | link image | alignleft aligncenter alignright alignjustify | numlist bullist outdent indent | removeformat | table',
                    relative_urls: false,
                    automatic_uploads: true,
                    images_upload_url: this.route('tip.processImage'),
                    file_picker_types: 'image',
                    file_picker_callback: function(cb, value, meta)
                    {
                        var input = document.createElement('input');
                        input.setAttribute('type', 'file');
                        input.setAttribute('accept', 'image/*');
                        input.onchange = function() {
                            var file = this.files[0];
                            var reader = new FileReader();
                            reader.readAsDataURL(file);
                            reader.onload = function ()
                            {
                                var id = 'blobid' + (new Date()).getTime();
                                var blobCache =  tinymce.activeEditor.editorUpload.blobCache;
                                var base64 = reader.result.split(',')[1];
                                var blobInfo = blobCache.create(id, file, base64);
                                blobCache.add(blobInfo);
                                cb(blobInfo.blobUri(), { title: file.name });
                            };
                        };
                        input.click();
                    }
                },
                dropzoneOptions: {
                    url: this.route('tips.submit-edit', this.tip_data.tip_id),
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
        methods: {
            validateForm(e)
            {
                e.preventDefault();
                if(this.$refs.newTipForm.checkValidity() === false || !this.form.equipment || this.form.tip == '')
                {
                    this.validated  = true;
                    this.validEquip = !this.form.equipment ? true : false;
                    this.validTip   = this.form.tip == '' ? true : false;
                }
                else
                {
                    this.validated  = false;
                    this.validEquip = false;
                    this.validTip   = false;
                    this.button.dis = true;
                    this.button.text = 'Loading...';
                    this.submitForm();
                }
            },
            submitForm()
            {
                var myDrop = this.$refs.fileDropzone;
                if(myDrop.getQueuedFiles().length > 0)
                {
                    this.showProgress = true;
                    myDrop.processQueue();
                }
                else
                {
                    this.updateTip();
                }
            },
            updateTip()
            {
                this.form._completed = true;
                axios.put(this.route('tips.update', this.tip_data.tip_id), this.form)
                    .then(res => {
                        console.log(res);
                        window.location.href = this.route('tip.details', [this.tip_data.tip_id, this.dashify(this.form.subject)]);
                    }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error));
            },
            updateProgressBar(progress)
            {
                this.progress = progress;
            },
            sendingFiles(file, xhr, formData)
            {
                formData.append('_token', this.form.token);
                formData.append('subject', this.form.subject);
                formData.append('tip', this.form.tip);
                formData.append('tipType', this.form.tipType);
                formData.append('equipment', this.form.equipiment);
            },
            queueComplete()
            {
                //
                this.button.text = 'Processing, please wait';
                this.updateTip();
            },
            delFile(index, fileID)
            {
                this.form.activeFileList.splice(index, 1);
                this.form.deletedFileList.push(fileID);
            }
        }
    }
</script>
