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
                    v-model="form.equipment"
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
                    <file-upload
                        v-show="showFile"
                        ref="fileUpload"
                        :submit_url="route('tips.store')"
                        @uploadFinished="createTip">
                    </file-upload>
                </transition>
            </div>
            <div class="mb-2">
                <div class="row justify-content-center">
                    <div class="col-md-2">
                        <b-button size="sm" pill @click="showAdv = !showAdv" variant="primary" block>
                            Advanced Options
                            <i v-if="showAdv" class="fas fa-angle-down"></i>
                            <i v-else class="fas fa-angle-right"></i>
                        </b-button>
                    </div>
                </div>
                <transition name="fade" v-show="showAdv">
                    <div v-if="showAdv" class="mt-2 mb-2">
                        <b-form-checkbox v-model="form.noEmail" switch class="text-center">Supress Notification</b-form-checkbox>
                    </div>
                </transition>
            </div>
            <form-submit
                button_text="Create Tech Tip"
                :submitted="submitted"
            ></form-submit>
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
        new_tip: {
            type:     Boolean,
            required: false,
            default:  true,
        },
    },
    data() {
        return {
            error:       false,
            submitted:   false,
            validated:   false,
            showFile:    false,
            showAdv:     false,
            showOverlay: false,
            form: {
                subject:     null,
                description: null,
                tip_type_id: null,
                equipment:   null,
                description: null,
                noEmail:     false,
            },
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
                    this.createTip();
                }
            }
        },
        uploadFinished()
        {
            this.createTip();
        },
        createTip()
        {
            this.showOverlay = true;
            axios.post(this.route('tips.store'), this.form)
                .then(res => {
                    window.location.href = this.route('tip.details', [res.data.success, this.dashify(this.form.subject)]);
                }).catch(error => this.$bvModal.msgBoxOk('Create new Tech Tip operation failed.  Please try again later.'));
        }
    }
}
</script>
