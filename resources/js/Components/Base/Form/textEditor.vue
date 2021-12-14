<template>
    <ValidationProvider v-slot="v" :rules="rules" :mode="mode">
        <b-form-group
            :label="label+':'"
            :label-for="name"
        >
            <template slot="label">
                <slot name="label"></slot>
            </template>
            <editor
                v-model="curVal"
                api-key="no-api-key"
                :init="editorInit"
            ></editor>
            <b-form-invalid-feedback :state="false">{{v.errors[0]}}</b-form-invalid-feedback>
            <b-form-invalid-feedback :state="false" v-if="errors && errors[name]">{{errors[name]}}</b-form-invalid-feedback>
        </b-form-group>
    </ValidationProvider>
</template>

<script>
    /*
    *   If the editor is inside a BootstrapVue Modal, be sure to set the "no-enforce-focus" property
    */
    export default {
        props: {
            value: {
                type:    [String, Number],
                default: '',
            },
            rules: {
                type:    [String, Object],
                default: '',
            },
            label: {
                type:    String,
                default: '',
            },
            name: {
                type:    String,
                default: '',
            },
            placeholder: {
                type:     String,
                required: false,
                default: '',
            },
            mode: {
                type:     String,
                default: 'eager',
            },
            allow_image: {
                type:    Boolean,
                default: false,
            }
        },
        data() {
            return {
                curVal: this.value,
                editorInit: {
                    plugins             : 'autolink advlist lists link image table spellchecker fullscreen preview',
                    height              : 500,
                    browser_spellcheck  : true,
                    toolbar             : 'formatselect spellchecker | bold italic strikethrough forecolor | alignleft aligncenter alignright alignjustify | numlist bullist outdent indent | removeformat | table | fullscreen preview link image',
                    relative_urls       : false,
                    automatic_uploads   : true,
                    images_upload_url   : route('upload-image'),
                    file_picker_types   : 'image',
                    image_dimensions    : false,
                    image_class_list    : [
                        {
                            title: 'Responsive',
                            value: 'img-fluid'
                        }
                    ],
                    file_picker_callback: function(cb, value, meta)
                    {
                        var input = document.createElement('input');
                        input.setAttribute('type', 'file');
                        input.setAttribute('accept', 'image/*');
                        input.onchange = function() {
                            var file   = this.files[0];
                            var reader = new FileReader();
                            reader.readAsDataURL(file);
                            reader.onload = function ()
                            {
                                var id        = 'blobid' + (new Date()).getTime();
                                var blobCache = tinymce.activeEditor.editorUpload.blobCache;
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
        watch: {
            curVal(val)
            {
                this.$emit('input', val);
            },
            value(newVal)
            {
                this.curVal = newVal;
            }
        },
        computed: {
            image()
            {
                return this.allow_image ? 'image' : '';
            },
            errors()
            {
                return this.$page.props.errors;
            },
        },
        methods: {
            emitBlur()
            {
                this.$emit('blur');
            },
            emitUpdate()
            {
                this.$emit('update');
            },
            emitChange()
            {
                this.$emit('change');
            },
            state(v)
            {
                if(v.untouched || this.hideState)
                {
                    return null;
                }

                return v.valid;
            },
        }
    }
</script>
