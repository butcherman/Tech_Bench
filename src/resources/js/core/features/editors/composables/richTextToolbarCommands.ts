import { linkEditorModal } from "./linkEditorModal";
import type {
    RichTextToolbarItemId,
    RichTextCommandDefinition,
} from "@/core/features/editors/types/richText";

export const richTextToolbarCommands = {
    bold: {
        execute: (editor) => editor.chain().focus().toggleBold().run(),
        isActive: (editor) => editor.isActive("bold"),
    },

    italic: {
        execute: (editor) => editor.chain().focus().toggleItalic().run(),
        isActive: (editor) => editor.isActive("italic"),
    },

    underline: {
        execute: (editor) => editor.chain().focus().toggleUnderline().run(),
        isActive: (editor) => editor.isActive("underline"),
    },

    strike: {
        execute: (editor) => editor.chain().focus().toggleStrike().run(),
        isActive: (editor) => editor.isActive("strike"),
    },

    heading1: {
        execute: (editor) =>
            editor.chain().focus().toggleHeading({ level: 1 }).run(),
        isActive: (editor) => editor.isActive("heading", { lavel: 1 }),
    },

    heading2: {
        execute: (editor) =>
            editor.chain().focus().toggleHeading({ level: 2 }).run(),
        isActive: (editor) => editor.isActive("heading", { lavel: 2 }),
    },

    heading3: {
        execute: (editor) =>
            editor.chain().focus().toggleHeading({ level: 3 }).run(),
        isActive: (editor) => editor.isActive("heading", { lavel: 3 }),
    },

    bulletList: {
        execute: (editor) => editor.chain().focus().toggleBulletList().run(),
        isActive: (editor) => editor.isActive("bulletList"),
    },

    orderedList: {
        execute: (editor) => editor.chain().focus().toggleOrderedList().run(),
        isActive: (editor) => editor.isActive("orderedList"),
    },

    blockquote: {
        execute: (editor) => editor.chain().focus().toggleBlockquote().run(),
        isActive: (editor) => editor.isActive("blockquote"),
    },

    code: {
        execute: (editor) => editor.chain().focus().toggleCode().run(),
        isActive: (editor) => editor.isActive("code"),
    },

    undo: {
        execute: (editor) => editor.chain().focus().undo().run(),
        canExecute: (editor) => editor.can().chain().focus().undo().run(),
    },

    horizontalRule: {
        execute: (editor) => editor.chain().focus().setHorizontalRule().run(),
        canExecute: (editor) =>
            editor.can().chain().focus().setHorizontalRule().run(),
    },

    redo: {
        execute: (editor) => editor.chain().focus().redo().run(),
        canExecute: (editor) => editor.can().chain().focus().redo().run(),
    },

    link: {
        execute: (editor) => linkEditorModal(editor),
        isActive: (editor) => editor.isActive("link"),
    },
} satisfies Record<RichTextToolbarItemId, RichTextCommandDefinition>;
