<template>
    <div>
        <b-alert variant="success" :show="successAlert"><h3 class="text-center">File Uploaded</h3></b-alert>
        <b-form @submit="submitFile" method="post" enctype="multipart/form-data" novalidate :validated="validated" ref="guestNewFileForm">
            <input type="hidden" name="_token" :value=token />
            <b-form-group
                label="Your Name:"
                label-for="name">
                <b-form-input
                    id="name"
                    type="text"
                    name="name"
                    placeholder="Enter Your Name"
                    required
                    v-model=form.name>
                </b-form-input>
                <b-form-invalid-feedback v-show="validated">Please Enter Your Name</b-form-invalid-feedback>
            </b-form-group>
            <vue-dropzone
                id="dropzone"
                class="filedrag"
                ref="myVueDropzone"
                @vdropzone-upload-progress="updateProgressBar"
                @vdropzone-sending="sendingFiles"
                @vdropzone-queue-complete="completedUpload"
                :options="dropzoneOptions">
            </vue-dropzone>
            <b-form-invalid-feedback v-show="validatedFileErr">A File Must Be Attached</b-form-invalid-feedback>
            <b-form-textarea
                id="comments"
                v-model=form.comments
                placeholder="Tell Me Something About This File..."
                rows=10
                class="mb-2"
            ></b-form-textarea>
            <b-progress v-show="showProgress" :value="progress" variant="success" striped animate show-progress></b-progress>
            <b-button type="submit" block variant="primary" :disabled="button.disable">{{button.text}}</b-button>
        </b-form>
    </div>
</template>

<script>
    export default {
        props: [
            'link_id'
        ],
        data() {
            return {
                token: window.techBench.csrfToken,
                validated: false,
                validatedFileErr: false,
                form: {
                    name: '',
                    comments: '',
                },
                button: {
                    text: 'Upload Files',
                    disable: false,
                },
                dropzoneOptions: {
                    url: this.route('file-links.show', this.link_id),
                    autoProcessQueue: false,
                    parallelUploads: 1,
                    maxFiles: 5,
                    maxFilesize: window.techBench.maxUpload,
                    addRemoveLinks: true,
                    chunking: true,
                    chunkSize: window.chunkSize,
                    parallelChunkUploads: false,
                },
                successAlert: false,
                progress: 0,
                showProgress: false,
                numFiles: 0,
            }
        },
        methods:
        {
            submitFile(e)
            {
                e.preventDefault();
                //  Create the dropzone objects
                var myDrop = this.$refs.myVueDropzone;

                //  Validate the form to be sure all necessary information is inputted
                if(this.$refs.guestNewFileForm.checkValidity() === false)
                {
                    this.validated = true;
                }
                //  Make sure there is a file attached
                else if(myDrop.getQueuedFiles().length < 1)
                {
                    this.validatedFileErr = true;
                }
                //  Valid form - begin upload
                else
                {
                    this.validated = false;
                    this.validatedFileErr = false;
                    this.button.text  = 'Loading...';
                    this.button.disable   = true;
                    this.successAlert = false;
                    this.showProgress = true;

                    this.numFiles = myDrop.getQueuedFiles().length;
                    myDrop.processQueue();
                }
            },
            sendingFiles(file, xhr, formData)
            {
                formData.append('_token', this.token);
                formData.append('name', this.form.name);
                formData.append('note', this.form.comments);
            },
            updateProgressBar(file, progress, sent)
            {
                var fileProgress = sent / file.size * 100;
                this.progress = Math.round(fileProgress);
            },
            completedUpload(file, res)
            {
                axios.put(this.route('file-links.show', this.link_id), {_complete: 'true', count: this.numFiles})
                    .then(res => {
                        this.$refs.myVueDropzone.removeAllFiles();
                        this.successAlert = true;
                        this.button.disable   = false;
                        this.button.text  = 'Upload Files'
                        this.showProgress = false;
                    }).catch(error => {
                        this.successAlert = true;
                    });
            },
        }
    }
</script>
