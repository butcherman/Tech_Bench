<template>
    <div>
        <b-alert variant="success" :show="successAlert"><h3 class="text-center">File(s) Uploaded</h3></b-alert>
        <b-form @submit="submitFile" novalidate :validated="validated" ref="guestNewFileForm">
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
            <file-upload ref="fileUpload"
                :submit_url="route('file-links.show', this.link_id)"
                @uploadFinished="uploadFinished">
            </file-upload>
            <b-form-invalid-feedback v-show="validatedFileErr">A File Must Be Attached</b-form-invalid-feedback>
            <b-form-textarea
                id="comments"
                v-model=form.comments
                placeholder="Tell Me Something About This File..."
                rows=10
                class="mb-2"
            ></b-form-textarea>
            <form-submit
                :button_text="button.text"
                :submitted="submitted"
            ></form-submit>
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
                validated: false,
                validatedFileErr: false,
                submitted: false,
                form: {
                    name: '',
                    comments: '',
                },
                button: {
                    text: 'Upload Files',
                },
                successAlert: false,
                numFiles: 0,
            }
        },
        methods:
        {
            submitFile(e)
            {
                e.preventDefault();
                this.successAlert = false;
                var fileZone = this.$refs.fileUpload;
                if(this.$refs.guestNewFileForm.checkValidity() === false)
                {
                    this.validated = true;
                }
                else if(fileZone.getFileCount() == 0)
                {
                    this.validatedFileErr = true;
                }
                else
                {
                    this.submitted = true;
                    this.validatedFileErr = false;
                    this.numFiles = fileZone.getFileCount();
                    fileZone.submitFiles(this.form);
                }
            },
            //  Finalize upload and send notification
            uploadFinished()
            {
                axios.put(this.route('file-links.show', this.link_id), {_complete: 'true', count: this.numFiles})
                    .then(res => {
                        this.successAlert = true;
                        this.button.text  = 'Upload Files';
                        this.form.comments = '';
                        this.submitted = false;
                        this.$refs.fileUpload.reset();
                    }).catch(error => {
                        this.$bvModal.msgBoxOk('File upload failed.  Please try again later.')
                    });
            }
        }
    }
</script>
