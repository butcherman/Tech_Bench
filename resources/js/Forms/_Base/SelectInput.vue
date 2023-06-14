<template>
    <div class="mb-3">
        <label v-if="label" :for="id" class="form-label w-100">
            {{ label }}:
        </label>
        <select
            v-model="value"
            class="form-select"
            :class="{ 'is-valid': isValid, 'is-invalid': isInvalid }"
        >
            <template v-for="(option, key) in list" :key="key">
                <template v-if="Array.isArray(option)">
                    <option>Is array</option>
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
    list: any[];
    textField?: string;
    valueField?: string;
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
