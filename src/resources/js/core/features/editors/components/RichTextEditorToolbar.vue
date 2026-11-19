<script setup lang="ts">
import RichTextEditorToolbarItem from "./RichTextEditorToolbarItem.vue";
import type { Editor } from "@tiptap/vue-3";
import type { RichTextToolbarItem } from "@/core/features/editors/types/richText.js";

const props = defineProps<{
    editor: Editor;
    items: RichTextToolbarItem[][];
}>();
</script>

<template>
    <div
        class="flex flex-row flex-wrap gap-3 border border-slate-300 rounded-lg p-1 divide-x divide-slate-400"
    >
        <div
            v-for="(group, index) in items"
            :key="index"
            class="flex flex-row gap-1"
        >
            <template v-for="item in group" :key="item.command">
                <RichTextEditorToolbarItem :editor="editor" :item="item" />
            </template>
        </div>
    </div>
</template>
