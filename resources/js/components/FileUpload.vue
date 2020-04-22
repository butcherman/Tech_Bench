<template>
    <div>
        <vue-dropzone
            id="dropzone"
            class="filedrag"
            ref="fileDropzone"
            @vdropzone-upload-progress="updateProgressBar"
            @vdropzone-sending="sendingFiles"
            @vdropzone-queue-complete="queueComplete"
            @vdropzone-max-files-exceeded="tooManyFiles"
            @vdropzone-removed-file="removedFile"
            :options="dropzoneOptions">
        </vue-dropzone>
        <b-progress v-show="showProgress" :value="progress" variant="success" striped animate show-progress></b-progress>
    </div>
</template>

<script>
    export default {
        props: {
            // 'submit_url',
            submit_url: {
                type: String,
                required: true,
            },
            max_files: {
                type: Number,
                required: false,
                default: 5
            }
        },
        data: function () {
            return {
                formData: {},
                showProgress: false,
                fileCount: false,
                progress: 0,
                alreadyWarned: false,
                dropzoneOptions: {
                    url: this.submit_url,
                    autoProcessQueue: false,
                    parallelUploads: 1,
                    maxFiles: this.max_files,
                    maxFilesize: window.techBench.maxUpload,
                    addRemoveLinks: true,
                    chunking: true,
                    chunkSize: window.chunkSize,
                    parallelChunkUploads: false,
                    dictDefaultMessage: '<i class="fas fa-cloud-upload-alt"></i> Drag File Here or Click to Upload'
                },
            }
        },
        methods: {
            //  Send number of attached files to parent
            getFileCount()
            {
                return this.$refs.fileDropzone.getQueuedFiles().length
            },
            //  Submit the files and attached form
            submitFiles(form)
            {
                this.showProgress = true;
                this.formData = form;
                this.$refs.fileDropzone.processQueue();
            },
            //  Update progress bar with total upload progress
            updateProgressBar(file, progress, sent)
            {
                var fileProgress = sent / file.size * 100;
                this.progress = Math.round(fileProgress);
            },
            //  Send each individual chunk
            sendingFiles(file, xhr, formData)
            {
                //  Include all form information so that validation checks pass
                formData.append('_token', window.techBench.csrfToken);
                for(let [key, value] of Object.entries(this.formData))
                {
                    formData.append(key, value);
                }
            },
            //  Notify event that upload is finished
            queueComplete(file, res)
            {
                this.$emit('uploadFinished', res);
            },
            //  Reset data so that the dropbox can be used again
            reset()
            {
                this.$refs.fileDropzone.removeAllFiles();
                this.formData =  {};
                this.showProgress =  false;
                this.fileCount =  false;
                this.progress =  0;
            },
            //  Alert user that too many files have been selected
            tooManyFiles()
            {
                if(!this.alreadyWarned)
                {
                    this.alreadyWarned = true;
                    this.$bvModal.msgBoxOk('Too many files selected.  You can only upload 5 files at a time', {
                        buttonSize: 'sm',
                        centered: true
                    });
                }
            },
            //  A file was removed
            removedFile()
            {
                if(this.$refs.fileDropzone.getQueuedFiles().length <= this.dropzoneOptions.maxFiles)
                {
                    this.alreadyWarned = false;
                }
            }
        },
    }
</script>
