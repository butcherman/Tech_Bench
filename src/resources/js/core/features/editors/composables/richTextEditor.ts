import StarterKit from "@tiptap/starter-kit";
import { useEditor } from "@tiptap/vue-3";
import type { UseRichTextEditorOptions } from "@/core/features/editors/types/richText";

export const useRichTextEditor = (options: UseRichTextEditorOptions = {}) => {
    const editor = useEditor({
        extensions: [
            StarterKit.configure({
                link: {
                    autolink: true,
                    linkOnPaste: true,
                    openOnClick: false,
                    HTMLAttributes: {
                        class: "text-blue-500 underline",
                    },
                },
            }),
        ],
        content: options.content ?? null,
        editable: options.editable ?? true,

        onUpdate: ({ editor }) => {
            options.onUpdate?.(editor.getJSON());
        },

        onFocus: () => {
            options.onFocus?.();
        },

        onBlur: () => {
            options.onBlur?.();
        },
    });

    return {
        editor,
    };
};
