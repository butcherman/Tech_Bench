<template>
    <div>
        <div class="row">
            <div class="col-12 grid-margin">
                <h4 class="text-center text-md-left">Logo</h4>
            </div>
        </div>
        <b-overlay :show="loading">
            <template v-slot:overlay>
                <atom-loader></atom-loader>
            </template>
            <div class="row justify-content-center">
                <div class="col-md-4 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title">Current Logo</div>
                            <img :src="logo" alt="Tech Bench Logo" class="img-thumbnail" />
                        </div>
                    </div>
                </div>
                <div class="col-md-4 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title">Drag New Logo Below</div>
                            <vue-dropzone
                                class="mt-auto"
                                id="upload-logo"
                                ref="upload-logo"
                                :options="dropzoneOptions"
                                @vdropzone-success="processUpload"
                            ></vue-dropzone>
                        </div>
                    </div>
                </div>
            </div>
        </b-overlay>
    </div>
</template>

<script>
    import  App from '../../../Layouts/app';
    import  vue2Dropzone from 'vue2-dropzone'
    import 'vue2-dropzone/dist/vue2Dropzone.min.css'

    export default {
        layout: App,
        components: {
            vueDropzone: vue2Dropzone
        },
        props: {
            logo: {
                type: String,
                required: true,
            }
        },
        data() {
            return {
                loading: false,
                dropzoneOptions: {
                    url: route('admin.set-logo'),
                    uploadMultiple: false,
                    maxFiles: 1,
                    acceptedFiles: 'image/*',
                    headers: {
                        'X-CSRF-TOKEN': this.$page.props.app.fileData.token,
                    },
                }
            }
        },
        methods: {
            processUpload(res)
            {
                this.loading = true;
                this.$refs['upload-logo'].removeAllFiles();
                this.$refs['upload-logo'].disable();
                this.$refs['upload-logo'].enable();

                this.$inertia.reload({
                    onFinish: ()=> {
                        this.loading = false;
                    }
                });
            }
        }
    }
</script>
