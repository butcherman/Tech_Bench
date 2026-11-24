<script setup lang="ts">
import RichTextEditorToolbar from "./RichTextEditorToolbar.vue";
import { EditorContent } from "@tiptap/vue-3";
import { useRichTextEditor } from "@/core/features/editors/composables/richTextEditor.js";
import { computed, watch } from "vue";
import { richTextToolbarPresets } from "@/core/features/editors/composables/richTextToolbarPresets.js";
import { useRichTextToolbarHelper } from "@/core/features/editors/composables/richTextToolbarHelper.js";
import type { JSONContent } from "@tiptap/core";
import type {
    RichTextToolbarItemId,
    RichTextToolbarPreset,
} from "@/core/features/editors/types/richText.js";

const emit = defineEmits<{
    "update:modelValue": [JSONContent];
    focus: [];
    blur: [];
}>();

const props = defineProps<{
    modelValue: JSONContent | null;
    disabled?: boolean;
    toolbar?: RichTextToolbarPreset | RichTextToolbarItemId[];
}>();

const { resolveRichTextToolbar, normalizeToolbar } = useRichTextToolbarHelper();

const { editor } = useRichTextEditor({
    content: props.modelValue,
    editable: !props.disabled,

    onUpdate: (content) => {
        emit("update:modelValue", content);
    },

    onFocus: () => {
        emit("focus");
    },

    onBlur: () => {
        emit("blur");
    },
});

/**
 * Build the toolbar
 */
const toolbarItems = computed(() => {
    const toolbar = props.toolbar
        ? typeof props.toolbar === "string"
            ? richTextToolbarPresets[props.toolbar]
            : props.toolbar
        : richTextToolbarPresets.standard;

    return resolveRichTextToolbar(normalizeToolbar(toolbar));
});

/**
 * Synchronize external v-model changes
 * with the Tiptap document.
 */
watch(
    () => props.modelValue,
    (value) => {
        if (!editor.value || !value) {
            return;
        }

        if (JSON.stringify(editor.value.getJSON()) !== JSON.stringify(value)) {
            editor.value.commands.setContent(value);
        }
    },
);

/**
 * Synchronize disabled state.
 */
watch(
    () => props.disabled,
    (disabled) => {
        editor.value?.setEditable(!disabled);
    },
);
</script>

<template>
    <div class="editor flex flex-col gap-2">
        <RichTextEditorToolbar
            v-if="editor"
            :editor="editor"
            :items="toolbarItems"
        />
        <EditorContent
            :editor="editor"
            class="editor-content border border-slate-300 rounded-lg p-2"
        />
    </div>
</template>

<style scoped>
.editor {
    height: 100%;
    display: flex;
    flex-direction: column;
}

.editor-content {
    flex: 1;
    min-height: 0;
}

.editor-content :deep(.tiptap) {
    height: 100%;
    min-height: 0;
    overflow-y: auto;
    outline: none;
    border: none;
}
</style>
