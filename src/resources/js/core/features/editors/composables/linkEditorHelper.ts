import { getMarkRange } from "@tiptap/core";
import type { Editor } from "@tiptap/vue-3";
import type { LinkEditorData } from "../types/richText";

export const useLinkEditorHelper = (editor: Editor) => {
    /**
     * Get the full range of the link currently selected
     */
    const getLinkRange = () => {
        const { state } = editor;
        const { from } = state.selection;
        const linkMark = state.schema.marks.link;

        if (!linkMark) {
            return;
        }

        return (
            getMarkRange(state.doc.resolve(from), linkMark) ??
            getMarkRange(state.doc.resolve(Math.max(0, from - 1)), linkMark)
        );
    };

    /**
     * Get the text currently selected by the user.
     */
    const getSelectedText = () => {
        const { state } = editor;
        const { from, to } = state.selection;

        const linkRange = getLinkRange();

        if (linkRange) {
            return state.doc.textBetween(linkRange.from, linkRange.to, " ");
        }

        if (from === to) {
            return "";
        }

        return state.doc.textBetween(from, to, " ");
    };

    /**
     * Get the URL of the link at the current cursor/selection.
     */
    const getCurrentUrl = () => {
        const range = getLinkRange();

        if (!range) {
            return "";
        }

        return editor.getAttributes("link").href ?? "";
    };

    /**
     * Get all data needed to initialize the link modal.
     */
    const getLinkData = (): LinkEditorData => ({
        text: getSelectedText(),
        url: getCurrentUrl(),
    });

    /**
     * Determine whether the current selection is inside a link.
     */
    const isEditingLink = () => {
        return getLinkRange() !== undefined;
    };

    /**
     * Create a new link or update an existing link.
     */
    const setLink = ({ text, url }: LinkEditorData) => {
        const linkRange = getLinkRange();

        const chain = editor.chain().focus();

        if (linkRange) {
            chain
                .setTextSelection(linkRange)
                .insertContent({
                    type: "text",
                    text,
                    marks: [
                        {
                            type: "link",
                            attrs: {
                                href: url,
                            },
                        },
                    ],
                })
                .run();

            return;
        }

        chain
            .insertContent({
                type: "text",
                text,
                marks: [
                    {
                        type: "link",
                        attrs: {
                            href: url,
                        },
                    },
                ],
            })
            .run();
    };

    /**
     * Remove the link at the current cursor/selection.
     */
    const removeLink = () => {
        const linkRange = getLinkRange();

        const chain = editor.chain().focus();

        if (linkRange) {
            chain.setTextSelection(linkRange).unsetLink().run();

            return;
        }

        chain.unsetLink().run();
    };

    return {
        getLinkData,
        getSelectedText,
        getCurrentUrl,
        isEditingLink,
        setLink,
        removeLink,
    };
};
