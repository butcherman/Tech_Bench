<template>
    <div>
        <b-progress v-show="showProgress" :value="progress" variant="success" striped animate show-progress></b-progress>
        <vue-dropzone id="dropzone"
            class="filedrag"
            ref="fileDropzone"
            @vdropzone-upload-progress="updateProgressBar"
            @vdropzone-sending="sendingFiles"
            @vdropzone-queue-complete="queueComplete"
            :options="dropzoneOptions">
        </vue-dropzone>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                //
                token: window.techBench.csrfToken,
                dropzoneOptions: {
                    url: this.route('admin.module.upload'),
                    autoProcessQueue: true,
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
            updateProgressBar(file, progress, sent)
            {
                var fileProgress = sent / file.size * 100;
                this.progress = Math.round(fileProgress);
            },
            sendingFiles(file, xhr, formData)
            {
                formData.append('_token', this.token);
            },
            queueComplete()
            {
                var myDrop = this.$refs.fileDropzone;
                myDrop.removeAllFiles();
                this.showProgress = false;
                this.progress = 0;

                location.reload();
            },
        }
    }
</script>
