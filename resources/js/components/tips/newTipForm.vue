<template>
    <div>
        <b-form id="new-tip-form" @submit="submitNewTip">
            <b-form-group label="Subject:" label-for="subject">
                <b-form-input
                    id="subject"
                    v-model="form.subject"
                    type="text"
                    required
                    placeholder="Enter A Descriptive Subject"
                ></b-form-input>
            </b-form-group>
            <b-form-group label="System Types:" label-for="systems">
                <multiselect
                    v-model="form.systems"
                    :options="JSON.parse(system_types)"
                    :multiple="true"
                    group-values="data"
                    group-label="group"
                    label="name"
                    track-by="value"
                    :group-select="true"
                    placeholder="Select A System Type"
                ></multiselect>
            </b-form-group>
            <b-form-group label="Tip Type:" label-for="tip-type">
                <multiselect
                    v-model="form.tipType"
                    :options="JSON.parse(tip_types)"
                    :clear-on-select="false"
                    placeholder="Select A Tip Type"
                ></multiselect>
            </b-form-group>
            <b-form-group label="Tip Details:" label-for="tip">
                <editor :init="tinymce" v-model="form.tip"></editor>
            </b-form-group>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <h4 class="text-center pad-top">Attach File</h4>
                    <vue-dropzone  
                        id="dropzone"
                        class="filedrag"
                        ref="myVueDropzone" 
                        v-on:vdropzone-total-upload-progress="updateProgressBar"
                        v-on:vdropzone-sending="sendingFiles"
                        v-on:vdropzone-success="successUpload"
                        v-on:vdropzone-queue-complete="redirectToTip"
                        :options="dropzoneOptions">
                    </vue-dropzone>
                </div>
            </div>
            <b-progress v-show="showProgress" :value="progress" variant="success" striped animate show-progress></b-progress>
            <b-button type="submit" block variant="info" :disabled="button.dis">{{button.text}}</b-button>
        </b-form>
    </div>
</template>

<script>
    export default {
        props: [
            'tips_route',
            'image_route',
            'system_types',
            'tip_types',
        ],
        data () {
            return {
                form: {
                    token: window.techBench.csrfToken,
                    subject: '',
                    systems: [],
                    tipType: '',
                    tip:     '',
                },
                button: {
                    dis:   false,
                    text: 'Create Tech Tip',
                },
                tinymce: {
                    plugins: 'autolink advlist lists link image table',
                    height: 500,
                    browser_spellcheck: true,
                    toolbar: 'formatselect | bold italic strikethrough forecolor | link image | alignleft aligncenter alignright alignjustify | numlist bullist outdent indent | removeformat | table',
                    relative_urls: false,
                    automatic_uploads: true,
                    images_upload_url: this.image_route,
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
                    url: this.tips_route,
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
                newUrl: ''
            }
        },
        created()
        {
            //
        },
        methods: {
            submitNewTip(e)
            {
                e.preventDefault();
                
                this.button.text = 'Loading....';
                this.button.dis = true;
                
                var myDrop = this.$refs.myVueDropzone;
                
                if(myDrop.getQueuedFiles().length > 0)
                {
                    this.showProgress = true;
                    myDrop.processQueue();
                }
                else
                {
                    this.form.file = null;
                    axios.post(this.tips_route, this.form)
                        .then(res => {
                            if('url' in res.data)
                            {
                                this.url = res.data.url;
                                this.redirectToTip();
                            }
                            else
                            {
                                alert('There was an issue processing your request\nPlease try again later. \n\nError Info: '+res.data);
                            }
                        }).catch(error => alert('There was an issue processing your request\nPlease try again later. \n\nError Info: ' + error))
                }
            },
            sendingFiles(file, xhr, formData)
            {
                formData.append('_token', this.form.token);
                formData.append('subject', this.form.subject);
                formData.append('systems', JSON.stringify(this.form.systems));
                formData.append('tipType', this.form.tipType);
                formData.append('tip', this.form.tip);
            },
            updateProgressBar(progress)
            {
                this.progress = progress;
            },
            successUpload(file, res)
            {
                this.url = res.url;
            },
            redirectToTip()
            {
                window.location.replace(this.url);
            }
        }
    }
</script>
