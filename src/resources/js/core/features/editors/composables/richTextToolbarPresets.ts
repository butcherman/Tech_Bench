import type {
    RichTextToolbarDefinition,
    RichTextToolbarPreset,
} from "@/core/features/editors/types/richText";

export const richTextToolbarPresets = {
    minimal: ["bold", "italic", "underline"],

    standard: [
        ["undo", "redo"],
        ["bold", "italic", "underline", "strike"],
        ["heading1", "heading2", "heading3"],
        ["bulletList", "orderedList"],
        ["blockquote", "link"],
    ],
} satisfies Record<RichTextToolbarPreset, RichTextToolbarDefinition>;
