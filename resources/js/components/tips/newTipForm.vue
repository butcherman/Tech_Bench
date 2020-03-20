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
                    <h4 class="text-center pad-top">Attach File</h4>
                    <vue-dropzone
                        id="dropzone"
                        class="filedrag"
                        ref="fileDropzone"
                        @vdropzone-upload-progress="updateProgressBar"
                        @vdropzone-sending="sendingFiles"
                        @vdropzone-queue-complete="queueComplete"
                        :options="dropzoneOptions">
                    </vue-dropzone>
                </div>
            </div>
            <b-progress v-show="showProgress" :value="progress" variant="success" striped animate show-progress></b-progress>
            <div class="row justify-content-center mt-4">
                <div class="col-6 col-md-2 order-2 order-md-1">
                    <div class="onoffswitch">
                        <input type="checkbox" name="supressEmail" class="onoffswitch-checkbox" id="supressEmail" v-model="form.supressEmail">
                        <label class="onoffswitch-label" for="supressEmail">
                            <span class="onoffswitch-inner"></span>
                            <span class="onoffswitch-switch"></span>
                        </label>
                    </div>
                </div>
                <div class="col-md-4 align-self-center order-1 order-md-2">
                    <h5 class="text-left">Supress Email Notification</h5>
                </div>
            </div>
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
        ],
        data () {
            return {
                validated: false,
                validEquip: false,
                validTip: false,
                attachFile: false,
                form: {
                    token: window.techBench.csrfToken,
                    subject: '',
                    tip: '',
                    tipType: null,
                    equipment: null,
                    supressEmail: false,
                },
                button: {
                    text: 'Create Tech Tip',
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
                    url: this.route('tips.store'),
                    autoProcessQueue: false,
                    parallelUploads: 1,
                    maxFiles: 5,
                    maxFilesize: window.techBench.maxUpload,
                    addRemoveLinks: true,
                    chunking: true,
                    chunkSize: window.chunkSize,
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
                    this.createTip();
                }
            },
            createTip()
            {
                this.form._completed = true;
                axios.post(this.route('tips.store'), this.form)
                    .then(res => {
                        window.location.href = this.route('tip.details', [res.data.tip_id, this.dashify(this.form.subject)]);
                    }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error));
            },
            updateProgressBar(file, progress, sent)
            {
                var fileProgress = 100 - (file.size / sent * 100);
                this.progress = Math.round(fileProgress);
            },
            sendingFiles(file, xhr, formData)
            {
                formData.append('_token', this.form.token);
                formData.append('subject', this.form.subject);
                formData.append('supressEmail', this.form.supressEmail);
                formData.append('tip', this.form.tip);
                formData.append('tipType', this.form.tipType);
                formData.append('equipment', this.form.equipiment);
            },
            queueComplete()
            {
                //
                this.button.text = 'Processing, please wait';
                this.createTip();
            }
        }
    }
</script>
