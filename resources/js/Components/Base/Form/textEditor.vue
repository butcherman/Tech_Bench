<template>
    <ValidationProvider v-slot="v" :rules="rules" :mode="mode">
        <b-form-group
            :label="label+':'"
            :label-for="name"
        >
            <template slot="label">
                <slot name="label"></slot>
            </template>
            <quill-editor v-model="curVal" :options="editorOptions"></quill-editor>
            <b-form-invalid-feedback :state="false">{{v.errors[0]}}</b-form-invalid-feedback>
            <b-form-invalid-feedback :state="false" v-if="errors && errors[name]">{{errors[name]}}</b-form-invalid-feedback>
        </b-form-group>
    </ValidationProvider>
</template>

<script>
    import { quillEditor } from 'vue-quill-editor'
    // import Quill from 'quill';

    export default {
        components: { quillEditor },
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
            errors: {
                type:     Object,
                required: false,
                default:  null,
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
        },
        data() {
            return {
                curVal: this.value,
                editorOptions: {
                    placeholder: this.placeholder,
                    modules: {
                        toolbar: [
                            ['bold', 'italic', 'underline', 'strike'],
                            [{size: ['small', false, 'large', 'huge']}],
                            ['blockquote', 'code-block'],
                            [{list: 'ordered'}, {list: 'bullet'}],
                            [{ 'indent': '-1'}, { 'indent': '+1' }],
                            [{ 'align': [] }],
                            ['clean']
                        ],
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
            //
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
            }
        }
    }
</script>
