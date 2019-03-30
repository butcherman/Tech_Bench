<template>
    <div class="row justify-content-center pad-top">
        <div class="col-md-4">
            <img :src="logoUrl" alt="Company Logo" class="img-thumbnail" id="header-logo" />
        </div>
        <div class="col-md-4">
            <vue-dropzone
                id="dropzone"
                class="filedrag"
                ref="myVueDropzone"
                :options="dropzoneOptions"
                v-on:vdropzone-success="completedUpload"
            ></vue-dropzone>
            <p class="text-center">Drag new logo in the box above to change</p>
        </div>
    </div>
</template>

<script>
export default {
    props:
    [
        'logo',
        'submit_route',
    ],
    data() {
        return {
            token: window.techBench.csrfTokan,
            logoUrl: this.logo,
            dropzoneOptions: {
                url: this.submit_route,
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
            this.logoUrl = JSON.parse(res.xhr.response).url;
            this.$refs.myVueDropzone.removeAllFiles();
        }
    }
}
</script>
