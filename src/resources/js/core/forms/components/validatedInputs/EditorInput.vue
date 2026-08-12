<script setup lang="ts">
import BaseEditorInput from "../baseInputs/BaseEditorInput.vue";
import { useValidationHelper } from "../../composables/validationHelper.js";
import { toRef } from "vue";
import type { JSONContent } from "@tiptap/core";

const emit = defineEmits<{
    focus: [];
    blur: [];
}>();

const props = defineProps<{
    name: string;

    autocomplete?: string;
    disabled?: boolean;
    errorMessage?: string;
    helpMessage?: string;
    helpVisible?: boolean;
    label?: string;
}>();

const { errorMessage, value } = useValidationHelper<JSONContent>(
    toRef(props.name),
);
</script>

<template>
    <BaseEditorInput
        v-bind="props"
        v-model:value="value"
        :error-message="errorMessage"
        @focus="$emit('focus')"
        @blur="$emit('blur')"
    />
</template>
