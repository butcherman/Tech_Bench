<template>
    <div>
        <div class="row justify-content-center">
            <div class="col-md-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Current Logo</div>
                        <img :src="logo" alt="Company Logo" class="img-thumbnail" id="header-logo" />
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
                            v-on:vdropzone-success="completedUpload"
                        ></vue-dropzone>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props:
    [
        'logo',
    ],
    data() {
        return {
            token: window.techBench.csrfTokan,
            dropzoneOptions: {
                url: this.route('admin.submitLogo'),
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
            this.logo = JSON.parse(res.xhr.response).url;
            this.$refs.myVueDropzone.removeAllFiles();
        }
    }
}
</script>
