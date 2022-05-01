<!--
    dropdownInput is a standard dropdown box
    Note:  component uses VeeValidate and form must be wrapped in Validation Observer

    Props:
        value:       for v-model binding (required)
        rules:       array of VeeValidate rules
        label:       String for label field
        name:        String for name field of input
        placeholder: String for placeholder
        textField:   String to identify array key with text
        valueField:  String to identify array key with value
        options:     Array of options that will be in the list
                     Example:
                        [
                            {
                                text:  'option 1',
                                value: 'opt1',
                            },
                            {
                                text:  'option 2',
                                value: 'opt2',
                            }
                        ]
-->

<template>
    <ValidationProvider v-slot="v" :rules="rules">
        <b-form-group
            :label="label+':'"
            :label-for="name"
        >
            <template slot="label">
                <slot name="label"></slot>
            </template>
            <b-form-select
                v-model="curVal"
                :value-field="valueField"
                :text-field="textField"
                :options="options"
                :placeholder="placeholder"
                @change="emitChange"
            >
                <slot />
            </b-form-select>
            <b-form-invalid-feedback :state="false">{{v.errors[0]}}</b-form-invalid-feedback>
            <b-form-invalid-feedback :state="false" v-if="errors && errors[name]">{{errors[name]}}</b-form-invalid-feedback>
        </b-form-group>
    </ValidationProvider>
</template>

<script>
    export default {
        props: {
            value: {
                type:    String|Object,
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
                default:  '',
            },
            textField: {
                type:     String,
                required: false,
                default: 'text',
            },
            valueField: {
                type:     String,
                required: false,
                default: 'value',
            },
            options: {
                type:     Array,
                required: false,
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
            errors()
            {
                return this.$page.props.errors;
            },
        },
        methods: {
            emitChange(val)
            {
                this.$emit('change', val);
            }
        },
    }
</script>
