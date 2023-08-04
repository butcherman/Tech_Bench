<template>
    <div class="mb-3">
        <label :for="id" class="form-label w-100">
            {{ label }}:
        </label>
        <textarea
            v-bind="value"
            :rows="getRows"
            :id="id"
            :disabled="disabled"
            :class="{ 'is-valid': isValid, is_invalid: isInvalid }"
            class="form-control"
            @change="$emit('change', value)"
        />
        <span
            v-if="errorMessage && (meta.dirty || meta.touched)"
            class="text-danger"
            >{{ errorMessage }}</span
        >
    </div>
</template>

<script setup lang="ts">
import { toRef, computed } from "vue";
import { useField } from "vee-validate";

defineEmits(["change"]);

const props = defineProps<{
    id: string;
    name: string;
    label: string;
    placeholder?: string;
    disabled?: boolean;
    help?: string;
    rows?: number;
}>();

const isValid = computed<boolean>(() => {
    return meta.valid && meta.validated && !meta.pending;
});

const isInvalid = computed<boolean>(() => {
    return !meta.valid && meta.validated && !meta.pending;
});

const getRows = computed<number>(() => {
    return props.rows !== undefined ? props.rows : 3;
});

const nameRef = toRef(props, "name");
const { errorMessage, value, meta } = useField(nameRef);
</script>

<style>
label {
    font-weight: bold;
}
</style>
