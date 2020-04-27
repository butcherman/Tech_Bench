<template>
    <b-overlay :show="showOverlay">
        <template v-slot:overlay>
            <atom-spinner
                :animation-duration="1000"
                :size="60"
                color="#ff1d5e"
                class="mx-auto"
            />
            <h4 class="text-center">Processing...</h4>
        </template>
        <b-form @submit="validateForm" ref="techTipForm" novalidate :validated="validated">
            <b-form-group label="Subject:" label-for="subject">
                <b-form-input
                    id="subject"
                    v-model="form.subject"
                    type="text"
                    required
                    placeholder="Enter A Descriptive Subject"
                ></b-form-input>
                <b-form-invalid-feedback>Please Enter A Descriptive Subject</b-form-invalid-feedback>
            </b-form-group>
            <b-form-group label="Tip Type:" label-for="tip-type">
                <b-form-select
                    v-model="form.tip_type_id"
                    :options="tip_types"
                    required
                >
                    <template v-slot:first>
                        <option :value="null" disabled>Please Select An Option</option>
                    </template>
                </b-form-select>
                <b-form-invalid-feedback>Please Select A Tip Type</b-form-invalid-feedback>
            </b-form-group>
            <b-form-group label="Equipment Types:" label-for="equipment">
                <multiselect
                    id="equipment"
                    v-model="form.system_types"
                    placeholder="Select A System Type"
                    group-values="system_types"
                    group-label="name"
                    label="name"
                    track-by="sys_id"
                    :options="sys_types"
                    :multiple="true"
                    :allow-empty="false"
                    :group-select="true"
                    required
                ></multiselect>
                <b-form-invalid-feedback :state="validated && form.equipment == null ? false : null">You Must Select At Least One Equipment Type</b-form-invalid-feedback>
            </b-form-group>
            <b-form-group label="Tip Details:" label-for="description">
                <editor :init="tinymce" v-model="form.description" id="description"></editor>
                <b-form-invalid-feedback :state="validated && form.description == null ? false : null">What is a Tech Tip without an actual Tip?</b-form-invalid-feedback>
            </b-form-group>
            <div class="mb-2">
                <div class="row justify-content-center">
                    <div class="col-md-2">
                        <b-button size="sm" pill @click="showFile = !showFile" variant="primary" block>
                            File Upload
                            <i v-if="showFile" class="fas fa-angle-down"></i>
                            <i v-else class="fas fa-angle-right"></i>
                        </b-button>
                    </div>
                </div>
                <transition name="fade" v-show="showFile">
                    <div v-show="showFile" class="mt-3">
                        <div class="row justify-content-center" v-if="tip_data.files.length">
                            <div class="col-md-6">
                                <h4 class="text-center border-bottom">Current Files</h4>
                                <ul class="pl-5">
                                    <li v-for="(file, index) in files" :key="file.tip_file_id">
                                        <i class="far fa-trash-alt text-danger pointer mr-2" @click="deleteFile(file.tip_file_id, index)" title="Delete File" v-b-tooltip.hover></i>
                                        <a :href="route('download', [file.file_id, file.files.file_name])">
                                            {{file.files.file_name}}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <h4 class="text-center border-bottom">Add Files</h4>
                        <file-upload
                            ref="fileUpload"
                            :submit_url="route('tips.submit-edit', this.tip_data.details.tip_id)"
                            @uploadFinished="updateTip">
                        </file-upload>
                    </div>
                </transition>
            </div>
            <form-submit
                button_text="Update Tech Tip"
                :submitted="submitted"
            ></form-submit>
            <b-button block variant="danger" @click="deleteTip">Delete Tech Tip</b-button>
        </b-form>
    </b-overlay>
</template>

<script>
export default {
    props: {
        tip_types: {
            type:     Array,
            required: true,
        },
        sys_types: {
            type:     Array,
            required: true,
        },
        tip_data: {
            type: Object,
            required: true,
        }
    },
    data() {
        return {
            error:       false,
            submitted:   false,
            validated:   false,
            showFile:    false,
            showAdv:     false,
            showOverlay: false,
            form:        this.tip_data.details,
            files:       this.tip_data.files,
            tinymce: {
                plugins:             'autolink advlist lists link image table spellchecker fullscreen preview',
                height:               500,
                browser_spellcheck:   true,
                toolbar:             'formatselect spellchecker | bold italic strikethrough forecolor | alignleft aligncenter alignright alignjustify | numlist bullist outdent indent | removeformat | table | fullscreen preview link image',
                relative_urls:        false,
                automatic_uploads:    true,
                images_upload_url:    this.route('tip.processImage'),
                file_picker_types:   'image',
                file_picker_callback: function(cb, value, meta)
                {
                    var input = document.createElement('input');
                    input.setAttribute('type', 'file');
                    input.setAttribute('accept', 'image/*');
                    input.onchange    = function() {
                        var file      = this.files[0];
                        var reader    = new FileReader();
                        reader.readAsDataURL(file);
                        reader.onload = function ()
                        {
                            var id        = 'blobid' + (new Date()).getTime();
                            var blobCache =  tinymce.activeEditor.editorUpload.blobCache;
                            var base64    = reader.result.split(',')[1];
                            var blobInfo  = blobCache.create(id, file, base64);
                            blobCache.add(blobInfo);
                            cb(blobInfo.blobUri(), { title: file.name });
                        };
                    };
                    input.click();
                }
            }
        }
    },
    methods: {
        validateForm(e)
        {
            e.preventDefault();
            console.log(this.form);
            if(this.$refs.techTipForm.checkValidity() === false)
            {
                this.validated = true;
            }
            else
            {
                var fileZone = this.$refs.fileUpload;
                this.submitted = true;
                if(fileZone.getFileCount() > 0)
                {
                    fileZone.submitFiles(this.form);
                }
                else
                {
                    this.updateTip();
                }
            }
        },
        deleteFile(fileID, index)
        {
            if(!this.form.deletedFileList)
            {
                this.form.deletedFileList = [];
            }
            this.files.splice(index, 1);
            this.form.deletedFileList.push(fileID);
        },
        updateTip()
        {
            axios.put(this.route('tips.update', this.tip_data.details.tip_id), this.form)
                    .then(res => {
                        console.log(res);
                        window.location.href = this.route('tip.details', [this.tip_data.details.tip_id, this.dashify(this.form.subject)]);
                    }).catch(error => alert(error));
        },
        deleteTip()
        {
            this.$bvModal.msgBoxConfirm('Please confirm that you want to delete this Tech Tip.', {
                title: 'Please Confirm',
                size: 'sm',
                buttonSize: 'sm',
                okVariant: 'danger',
                okTitle: 'YES',
                cancelTitle: 'NO',
                footerClass: 'p-2',
                hideHeaderClose: false,
                centered: true
            })
            .then(value => {
                if(value)
                {
                    this.showOverlay = true;
                    axios.delete(this.route('tips.destroy', this.tip_data.details.tip_id))
                    .then(res => {
                        window.location.href = this.route('tips.index');
                    }).catch(error => this.$bvModal.msgBoxOk('Delete Tech Tip operation failed.  Please try again later.'));
                }
            })
            .catch(error => {
                this.$bvModal.msgBoxOk('Delete Tech Tip operation failed.  Please try again later.')
            });
        }
    }
}
</script>
