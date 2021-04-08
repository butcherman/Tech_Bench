<template>
    <ValidationProvider v-slot="v" :rules="rules" mode="eager">
        <b-form-group
            :label="label+':'"
            :label-for="name"
        >
            <template slot="label">
                <slot name="label"></slot>
            </template>
            <b-form-input
                :id="name"
                :name="name"
                :type="type"
                :placeholder="placeholder"
                :state="state(v)"
                v-model="curVal"
                @blur="emitBlur"
            ></b-form-input>
            <b-form-invalid-feedback :state="false">{{v.errors[0]}}</b-form-invalid-feedback>
            <b-form-invalid-feedback :state="false" v-if="errors && errors[name]">{{errors[name]}}</b-form-invalid-feedback>
        </b-form-group>
    </ValidationProvider>
</template>

<script>
    export default {
        props: {
            value: {
                type:    String,
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
            type: {
                type:    String,
                default: 'text',
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
            hideState: {
                type:    Boolean,
                default: false,
            }
        },
        data() {
            return {
                curVal: this.value,
            }
        },
        watch: {
            curVal(val)
            {
                this.$emit('input', val);
            },
        },
        computed: {

        },
        methods: {
            emitBlur()
            {
                this.$emit('blur');
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
