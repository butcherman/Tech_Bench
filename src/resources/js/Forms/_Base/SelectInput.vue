<template>
    <div class="mb-3">
        <label v-if="label" :for="id" class="form-label w-100">
            {{ label }}:
        </label>
        <select
            v-model="value"
            :id="id"
            class="form-select"
            :class="{ 'is-valid': isValid, 'is-invalid': isInvalid }"
            :disabled="disabled"
        >
            <option v-if="allowNull" :value="null"></option>
            <template v-for="(option, key) in list" :key="key">
                <template v-if="typeof option === 'string'">
                    <option :value="option">{{ option }}</option>
                </template>
                <template v-else-if="Array.isArray(option)">
                    <optgroup :label="key.toString()" :key="key">
                        <template
                            v-for="item in option"
                            :key="item[valueField] || item"
                        >
                            <option
                                :value="item[valueField] || item"
                                :disabled="item.disabled || false"
                            >
                                {{ item[textField] || item }}
                            </option>
                        </template>
                    </optgroup>
                </template>
                <template v-else>
                    <option :value="option[valueField]">
                        {{ option[textField] }}
                    </option>
                </template>
            </template>
        </select>
        <span
            v-if="errorMessage && (meta.dirty || meta.touched)"
            class="text-danger"
        >
            {{ errorMessage }}
        </span>
    </div>
</template>

<script setup lang="ts">
import { computed, toRef } from "vue";
import { useField } from "vee-validate";

const props = defineProps<{
    id: string;
    name: string;
    label?: string;
    list: any | any[];
    textField?: string;
    valueField?: string;
    allowNull?: boolean;
    disabled?: boolean;
}>();

const valueField = computed(() =>
    props.valueField ? props.valueField : "value"
);
const textField = computed(() => (props.textField ? props.textField : "text"));

const isValid = computed<boolean>(() => {
    return meta.valid && meta.validated && !meta.pending;
});

const isInvalid = computed<boolean>(() => {
    return !meta.valid && meta.validated && !meta.pending;
});

const nameRef = toRef(props, "name");
const { errorMessage, value, meta } = useField(nameRef);
</script>

<style scoped lang="scss">
label {
    font-weight: bold;
}
</style>
