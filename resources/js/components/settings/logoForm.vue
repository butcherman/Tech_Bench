<template>
    <div class="row justify-content-center">
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Current Logo:</div>
                    <b-overlay :show="submitted">
                        <template v-slot:overlay>
                            <atom-spinner
                                :animation-duration="1000"
                                :size="60"
                                color="#ff1d5e"
                                class="mx-auto"
                            />
                            <h4 class="text-center">Processing</h4>
                        </template>
                        <img :src="site_logo" alt="Tech Bench Logo" class="img-thumbnail" />
                    </b-overlay>
                </div>
            </div>
        </div>
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Drag New Logo Here to Replace</div>
                    <vue-dropzone
                        id="dropzone"
                        class="filedrag"
                        ref="myVueDropzone"
                        :options="dropzoneOptions"
                        @vdropzone-success="completedUpload"
                        @vdropzone-sending="submitted = true"
                    ></vue-dropzone>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            logo: {
                required: true,
            }
        },
        data() {
            return {
                site_logo: this.logo,
                submitted: false,
                dropzoneOptions: {
                    url: this.route('settings.submit_logo'),
                    uploadMultiple: false,
                    maxFiles: 1,
                    acceptedFiles: 'image/*',
                    headers: {
                        'X-CSRF-TOKEN': window.techBench.csrfToken
                    }
                }
            }
        },
        methods: {
            completedUpload(res)
            {
                this.site_logo = JSON.parse(res.xhr.response).url;
                this.$refs.myVueDropzone.removeAllFiles();
                this.submitted = false
            }
        }
    }
</script>
