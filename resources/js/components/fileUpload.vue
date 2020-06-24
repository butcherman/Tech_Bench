<template>
    <div>
        <vue-dropzone
            id="dropzone"
            class="filedrag"
            ref="file-dropzone"
            :options="dropzoneOptions"
            @vdropzone-upload-progress="updateProgressBar"
            @vdropzone-sending="sendingFiles"
            @vdropzone-queue-complete="queueComplete"
            @vdropzone-max-files-exceeded="tooManyFiles"
            @vdropzone-removed-file="removedFile"
            @vdropzone-file-added="addedFile"
            @vdropzone-error="showError"
        ></vue-dropzone>
        <b-progress v-show="progress.show" :value="progress.value" variant="success" striped animate show-progress></b-progress>
    </div>
</template>

<script>
    export default {
        props: {
            submit_route: {
                type:     String,
                required: true,
            },
            submit_method: {
                type:     String,
                required: false,
                default: 'post',
            },
            max_files: {
                type:     Number,
                required: false,
                default:  1,
            }
        },
        data() {
            return {
                dropzoneOptions: {
                    url:                  this.submit_route,
                    method:               this.submit_method,
                    autoProcessQueue:     false,
                    parallelUploads:      1,
                    maxFiles:             this.max_files,
                    maxFilesize:          window.techBench.maxUpload,
                    addRemoveLinks:       true,
                    chunking:             true,
                    chunkSize:            window.chunkSize,
                    parallelChunkUploads: false,
                    dictDefaultMessage:  '<i class="fas fa-cloud-upload-alt"></i> Drag File Here or Click to Upload',
                    headers: {
                        'X-CSRF-TOKEN': window.techBench.csrfToken,
                    }
                },
                progress: {
                    show:  false,
                    value: 0,
                },
                formData: {},
            }
        },
        created() {
            //
        },
        mounted() {
             //
        },
        computed: {
             //
        },
        watch: {
             //
        },
        methods: {
            //  Upload files to the server and submit the form
            submitFiles(form)
            {
                this.progress.show = true;
                this.formData = form;
                this.$refs['file-dropzone'].processQueue();
            },
            //  Count files attached to dropzone
            getFileCount()
            {
                return this.$refs['file-dropzone'].getQueuedFiles().length;
            },
            //  Calculate total upload progress
            updateProgressBar(file, progress, sent)
            {
                console.log('file - '+file);
                console.log('progress - '+progress);
                console.log('sent - '+sent);
                if(progress != 100)
                {
                    this.progress.value = progress;
                }
            },
            //  Send individual chunk - include form to pass validation rules
            sendingFiles(file, xhr, formData)
            {
                for(let [key, value] of Object.entries(this.formData))
                {
                    formData.append(key, value);
                }
            },
            //  Upload has completed
            queueComplete()
            {
                console.log('complete');
                this.$emit('upload-finished');
            },
            tooManyFiles()
            {
                console.log('too many files');
            },
            removedFile()
            {
                console.log('file removed');
            },
            addedFile(file)
            {
                this.$emit('file-added', file.name);
            },
            reset()
            {
                this.$refs['file-dropzone'].removeAllFiles();
                this.formData = {};
                this.progress.show = false;
                this.progress.value = 0;
            },
            showError(file, message, xhr)
            {
                var err = {
                    response: {
                        statusTest: message,
                        status: xhr.status
                    }
                }
                this.eventHub.$emit('axiosError', err)
            }
        }
    }
</script>
