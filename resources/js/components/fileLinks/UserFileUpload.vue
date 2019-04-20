<template>
    <b-form @submit="submitFile" method="post" :action=submit_route enctype="multipart/form-data">
        <b-alert variant="success" :show="successAlert"><h3 class="text-center">File Uploaded</h3></b-alert>
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
                v-model=name>
            </b-form-input>
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
        <b-form-textarea
            id="comments"
            v-model=comments
            placeholder="Tell Me Something About This File..."
            rows=10
            class="mb-2"
        ></b-form-textarea>
        <b-progress v-show="showProgress" :value="progress" variant="success" striped animate show-progress></b-progress>
        <b-button type="submit" block variant="primary" :disabled="button.dis">{{button.text}}</b-button>
    </b-form>
</template>

<script>
    export default {
        props: [
            'submit_route',
        ],
        data() {
            return {
                token: window.techBench.csrfToken,
                name: '',
                comments: '',
                button: {
                    text: 'Upload Files',
                    dis: false,
                },
                dropzoneOptions: {
                    url: this.submit_route,
                    autoProcessQueue: false,
                    parallelUploads: 1,
                    maxFiles: 5,
                    maxFilesize: window.techBench.maxUpload,
                    addRemoveLinks: true,
                    chunking: true,
                    chunkSize: 5000000,
                    parallelChunkUploads: false,
                },
                successAlert: false,
                progress: 0,
                showProgress: false,
            }
        },
        methods:
        {
            submitFile(e)
            {
                e.preventDefault();
                this.button.text  = 'Loading...';
                this.button.dis   = true;
                this.successAlert = false;
                this.showProgress = true;
                
                var myDrop = this.$refs.myVueDropzone;                 
                var myForm = new FormData();

                myDrop.processQueue();   
            },
            sendingFiles(file, xhr, formData)
            {                
                formData.append('_token', this.token);
                formData.append('name', this.name);
                formData.append('note', this.comments);
            },
            updateProgressBar(progress)
            {
                this.progress = progress;
            },
            completedUpload(file, res)
            {
                console.log(res);
                this.$refs.myVueDropzone.removeAllFiles();
                this.successAlert = true;
                this.button.dis   = false;
                this.button.text  = 'Upload Files'
                this.showProgress = false;
            },
        }
    }
</script>
