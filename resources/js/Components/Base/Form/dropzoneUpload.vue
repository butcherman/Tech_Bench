<template>
    <div class="my-2">
        <vue-dropzone
            id="dropzone-file"
            ref="dropzone-file"
            :options="dropzoneOptions"
            @vdropzone-file-added="newFile"
            @vdropzone-removed-file="removedFile"
            @vdropzone-sending="sendingFiles"
            @vdropzone-error="errorOccured"
            @vdropzone-error-multiple="errorOccured"
            @vdropzone-canceled="canceledFile"
            @vdropzone-total-upload-progress="emitTotalProgress"
            @vdropzone-queue-complete="queueComplete"
        ></vue-dropzone>
        <div class="invalid-feedback" :class="validationShow">{{validationError}}</div>
        <b-modal
            ref="upload-error-modal"
            size="xl"
            centered
            title="ERROR"
            ok-only
            header-text-variant="danger"
            title-class="text-center w-100"
            @hidden="resetDropzone"
        >
            <h4 class="text-center">Something Bad Happened</h4>
            <p class="text-center">There was a prolem during the file upload.  Please contact the system administrator</p>
            <p class="text-center" v-if="errors">{{errors.message}}</p>
            <div class="text-center" v-for="err in errors.errors" :key="err">
                <p v-for="e in err" :key="e">{{e}}</p>
            </div>
        </b-modal>
    </div>
</template>

<script>
    import  vue2Dropzone from 'vue2-dropzone'
    import 'vue2-dropzone/dist/vue2Dropzone.min.css'

    export default {
        components: {
            vueDropzone: vue2Dropzone
        },
        props: {
            url: {
                type:     String,
                required: false,
                default:  this.route('upload-file'),
            },
            maxFiles: {
                type:     Number,
                required: false,
                default:  1,
            },
            required: {
                type:     Boolean,
                required: false,
                default:  true,
            }
        },
        data() {
            return {
                dropzoneOptions: {
                    url:                  this.url,
                    autoProcessQueue:     false,
                    parallelUploads:      1,
                    maxFiles:             this.maxFiles,
                    maxFilesize:          this.$page.props.fileData.maxSize,
                    addRemoveLinks:       true,
                    chunking:             true,
                    chunkSize:            this.$page.props.fileData.chunkSize,
                    parallelChunkUploads: false,
                    dictDefaultMessage:  '<i class="fas fa-cloud-upload-alt"></i> Drag File Here or Click to Upload',
                    headers: {
                        'X-CSRF-TOKEN': this.$page.props.fileData.token,
                    },
                },
                formData: {},
                // errors: {},
                validationError: null,
                totalSize: 0,
                sent: 0,
            }
        },
        computed: {
            validationShow()
            {
                return this.validationError ? 'd-inline' : 'd-none';
            },
            errors:
            {
                get: function()
                {
                    return this.$page.props.errors;
                },

                set: function(setter)
                {
                    return setter;
                }
            },
        },
        methods: {
            //  Count files attached to dropzone
            getFileCount()
            {
                return this.$refs['dropzone-file'].getQueuedFiles().length;
            },
            //  Upload the file along with any form data
            process(form)
            {
                //  If the file is required, make sure it exists
                var queuedFiles = this.$refs['dropzone-file'].getQueuedFiles();
                if(this.required && queuedFiles.length == 0)
                {
                    this.validationError = 'You Must Select A File to Upload';
                    this.$emit('validation-error');
                }
                else
                {
                    //  Add the rest of the form to the form datat
                    this.formData = form;

                    //  Get the size of all files combined
                    queuedFiles.forEach((file) => {
                        this.totalSize += file.size;
                    });

                    //  Upload the files
                    this.$refs['dropzone-file'].processQueue();
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
            //  One or more files were added to the queue
            newFile(file)
            {
                this.validationError = null;
                this.$emit('file-added', file.name, file.size);
            },
            //  A file was removed from the dropzone box
            removedFile(file, error, xhr)
            {
                this.validationError = error;
            },
            //  Clear out all files and give a fresh instance
            resetDropzone()
            {
                this.$refs['dropzone-file'].removeAllFiles();
                this.$refs['dropzone-file'].disable();
                this.$refs['dropzone-file'].enable();
                this.$emit('dropzone-reset');
            },
            //  Display modal with any error messages that occured
            errorOccured(file, message, xhr)
            {
                if(xhr)
                {
                    this.$refs['upload-error-modal'].show();
                    this.errors = message;
                    // console.log(message);
                }
                else
                {
                    this.validationError = message;
                }
            },
            //  A file upload was canceled
            canceledFile()
            {
                this.$emit('upload-canceled');
            },
            //  File was uploaded successfully
            // successfullUpload(file, response)
            // {
            //     if(this.maxFiles == 1)
            //     {
            //         this.$emit('upload-progress', 100);
            //         this.$emit('completed', response);
            //         this.resetDropzone();
            //     }
            // },
            //  Multiple files were uploaded successfully
            // successfullUploadMulti(file, response)
            // {
            //     this.$emit('upload-progress', 100);
            //     this.$emit('completed', response);
            //     this.resetDropzone();
            // },
            //  All files are completed
            queueComplete()
            {
                this.$emit('upload-progress', 100);
                this.$emit('completed', 'success');
                this.resetDropzone();
            },
            //  Send out the total progress for all files in the queue
            emitTotalProgress(progress, bytes, sent)
            {
                //  Only update the sent if it was higher than what has already been sent
                //  Dropzone drops sent value when moving to new file
                if(sent > this.sent)
                {
                    this.sent = sent;
                }

                var progress = this.sent / this.totalSize * 100;
                this.$emit('upload-progress', progress);
            },
        },
    }
</script>
