<script setup lang="ts">
import BaseButton from "@/core/components/buttons/BaseButton.vue";
import { richTextToolbarCommands } from "@/core/features/editors/composables/richTextToolbarCommands.js";
import { computed } from "vue";
import type { Editor } from "@tiptap/vue-3";
import type {
    RichTextToolbarItem,
    RichTextCommandDefinition,
} from "@/core/features/editors/types/richText";

const props = defineProps<{
    editor: Editor;
    item: RichTextToolbarItem;
}>();

const command: RichTextCommandDefinition =
    richTextToolbarCommands[props.item.command];

const isActive = computed<boolean>(
    () => command.isActive?.(props.editor) ?? false,
);
const canExecute = computed<boolean>(
    () => command.canExecute?.(props.editor) ?? true,
);
</script>

<template>
    <BaseButton
        size="sm"
        variant="none"
        flat
        class="font-semibold"
        :icon="item.text ? '' : item.icon"
        :active="isActive"
        :disabled="!canExecute"
        :text="item.text"
        v-tooltip="item.label"
        @click="command.execute(editor)"
    />
</template>
