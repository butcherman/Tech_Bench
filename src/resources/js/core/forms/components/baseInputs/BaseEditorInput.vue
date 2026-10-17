<script setup lang="ts">
import BaseRichTextEditor from "@/core/features/editors/components/BaseRichTextEditor.vue";
import InputWrapper from "../wrappers/InputWrapper.vue";
import { useInputHelper } from "../../composables/inputHelper.js";
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

const inputValue = defineModel<JSONContent | null>({
    required: true,
});

const { hasFocus, onFocus, onBlur } = useInputHelper(props, emit);
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
