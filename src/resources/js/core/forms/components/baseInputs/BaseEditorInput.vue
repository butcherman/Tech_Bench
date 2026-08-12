<script setup lang="ts">
import BaseRichTextEditor from "@/core/features/editors/components/BaseRichTextEditor.vue";
import InputWrapper from "../wrappers/InputWrapper.vue";
import { computed } from "vue";
import { useInputHelper } from "../../composables/inputHelper.js";
import type { JSONContent } from "@tiptap/core";

const emit = defineEmits<{
    "update:value": [JSONContent];
    focus: [];
    blur: [];
}>();

const props = defineProps<{
    name: string;
    value: JSONContent;

    autocomplete?: string;
    disabled?: boolean;
    errorMessage?: string;
    helpMessage?: string;
    helpVisible?: boolean;
    label?: string;
}>();

const { hasFocus, onFocus, onBlur } = useInputHelper(props, emit);

const inputValue = computed({
    get: () => props.value,
    set: (value) => emit("update:value", value),
});
</script>

<template>
    <InputWrapper v-bind="props" :has-focus="hasFocus">
        <div class="block mb-2 text-sm font-medium text-muted">
            {{ label }}
        </div>
        <div class="editor-wrapper">
            <BaseRichTextEditor
                v-model:model-value="inputValue"
                :class="{ 'focus:border-blue-400!': hasFocus }"
                @focus="onFocus"
                @blur="onBlur"
            />
        </div>
    </InputWrapper>
</template>

<style scoped>
.editor-wrapper {
    height: 600px;
}
</style>
