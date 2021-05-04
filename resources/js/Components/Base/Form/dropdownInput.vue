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
            errors: {
                type:     Object,
                required: false,
                default:  null,
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
        methods: {
            emitChange(val)
            {
                this.$emit('change', val);
            }
        },
    }
</script>
