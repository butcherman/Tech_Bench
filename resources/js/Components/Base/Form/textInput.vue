<template>
    <ValidationProvider v-slot="v" :rules="rules">
        <b-form-group
            :label="label+':'"
            :label-for="name"
        >
            <b-form-input
                :id="name"
                :name="name"
                :type="type"
                :placeholder="label"
                v-model="curVal"
            ></b-form-input>
            <b-form-invalid-feedback :state="false">{{v.errors[0]}}</b-form-invalid-feedback>
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
            }
        },
    }
</script>
