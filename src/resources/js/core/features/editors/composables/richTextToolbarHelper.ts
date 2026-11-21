import { richTextToolbarItems } from "./richTextToolbarItems";
import type {
    RichTextToolbarDefinition,
    RichTextToolbarGroup,
    RichTextToolbarItem,
} from "@/core/features/editors/types/richText";

export const useRichTextToolbarHelper = () => {
    const resolveRichTextToolbar = (
        itemList: readonly RichTextToolbarGroup[],
    ): RichTextToolbarItem[][] => {
        return itemList.map((itemGroup) => {
            return itemGroup.map((item) => richTextToolbarItems[item]);
        });
    };

    const isGroupedToolbar = (
        toolbar: RichTextToolbarDefinition,
    ): toolbar is readonly RichTextToolbarGroup[] => {
        return toolbar.length > 0 && Array.isArray(toolbar[0]);
    };

    const normalizeToolbar = (
        toolbar: RichTextToolbarDefinition,
    ): readonly RichTextToolbarGroup[] => {
        if (toolbar.length === 0) {
            return [];
        }

        if (isGroupedToolbar(toolbar)) {
            return toolbar;
        }

        return [toolbar];
    };

    return {
        resolveRichTextToolbar,
        normalizeToolbar,
    };
};
