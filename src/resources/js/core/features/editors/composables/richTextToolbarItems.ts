import {
    faBold,
    faItalic,
    faUnderline,
    faStrikethrough,
    faHeading,
    faList,
    faListOl,
    faQuoteLeft,
    faCode,
    faMinus,
    faUndo,
    faRedo,
    faLink,
} from "@fortawesome/free-solid-svg-icons";

import type {
    RichTextToolbarItem,
    RichTextToolbarItemId,
} from "@/core/features/editors/types/richText";

export const richTextToolbarItems = {
    undo: {
        command: "undo",
        label: "Undo",
        icon: faUndo,
    },

    redo: {
        command: "redo",
        label: "Redo",
        icon: faRedo,
    },

    bold: {
        command: "bold",
        label: "Bold",
        icon: faBold,
    },

    italic: {
        command: "italic",
        label: "Italic",
        icon: faItalic,
    },

    underline: {
        command: "underline",
        label: "Underline",
        icon: faUnderline,
    },

    strike: {
        command: "strike",
        label: "Strikethrough",
        icon: faStrikethrough,
    },

    heading1: {
        command: "heading1",
        label: "Heading 1",
        icon: faHeading,
        text: "H1",
    },

    heading2: {
        command: "heading2",
        label: "Heading 2",
        icon: faHeading,
        text: "H2",
    },

    heading3: {
        command: "heading3",
        label: "Heading 3",
        icon: faHeading,
        text: "H3",
    },

    bulletList: {
        command: "bulletList",
        label: "Bullet List",
        icon: faList,
    },

    orderedList: {
        command: "orderedList",
        label: "Numbered List",
        icon: faListOl,
    },

    blockquote: {
        command: "blockquote",
        label: "Blockquote",
        icon: faQuoteLeft,
    },

    code: {
        command: "code",
        label: "Code",
        icon: faCode,
    },

    horizontalRule: {
        command: "horizontalRule",
        label: "Horizontal Rule",
        icon: faMinus,
    },

    link: {
        command: "link",
        label: "Link",
        icon: faLink,
    },
} satisfies Record<RichTextToolbarItemId, RichTextToolbarItem>;
