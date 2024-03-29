<!--
    datePicker Component shows a text box with calendar entry for date and time when selected
    Note:  component uses VeeValidate and form must be wrapped in Validation Observer

    Props:
        value:       for v-model binding (required)
        rules:       array of VeeValidate rules
        label:       String for label field
        name:        String for name field of input
        type:        String for input type
        placeholder: String for placeholder
        hideState:   Hide whether or not field has been modified
        mode:        rule processing mode
        list:        allows to use list prop in text box
        autofocus:   autofocus
-->

<template>
    <ValidationProvider v-slot="v" :rules="rules" :mode="mode">
        <b-form-group
            :label="label+':'"
            :label-for="name"
        >
            <template slot="label">
                <slot name="label"></slot>
            </template>
            <b-form-datepicker
                :id="name"
                :name="name"
                :type="type"
                :placeholder="placeholder"
                :state="state(v)"
                :list="list"
                :autofocus="autofocus"
                v-model="curVal"
                @blur="emitBlur"
                @change="emitChange"
                @update="emitUpdate"
            ></b-form-datepicker>
            <b-form-invalid-feedback :state="false">{{v.errors[0]}}</b-form-invalid-feedback>
            <b-form-invalid-feedback :state="false" v-if="errors && errors[name]">{{errors[name]}}</b-form-invalid-feedback>
        </b-form-group>
    </ValidationProvider>
</template>

<script>
    export default {
        props: {
            value: {
                type:    [String, Number, Date],
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
            placeholder: {
                type:     String,
                required: false,
                default: '',
            },
            hideState: {
                type:    Boolean,
                default: false,
            },
            mode: {
                type:     String,
                default: 'eager',
            },
            list: {
                type:    String,
                default: null,
            },
            autofocus: {
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
            value(newVal)
            {
                this.curVal = newVal;
            }
        },
        computed: {
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
            }
        }
    }
</script>
