<template>
    <ul class="h-full">
        <li v-if="!list.length">
            <h4 class="text-center">
                {{ emptyText ?? "Nothing to see here..." }}
            </h4>
        </li>
        <li
            v-for="item in list"
            class="p-3"
            :class="{
                border: !noBorder,
                'text-center': center,
                'pointer hover:bg-slate-200': item.href || hasLink(item),
            }"
            @click="handleClick($event, item)"
        >
            <slot name="item-row" :item="item">
                <fa-icon v-if="item.icon" :icon="item.icon" />
                {{ item[textField ?? "text"] }}
            </slot>
        </li>
    </ul>
</template>

<script setup lang="ts" generic="T extends listItem">
import { handleLinkClick } from "@/Composables/links.module";

export type listItem = {
    href?: string;
    icon?: string;
    text?: string;
};

const emit = defineEmits<{
    "row-clicked": [];
}>();

const props = defineProps<{
    list: T[];
    center?: boolean;
    noBorder?: boolean;
    emptyText?: string;
    textField?: keyof listItem;
    linkField?: keyof listItem;
    linkFn?: (row: any) => string;
}>();

/**
 * Determine if the row is clickable.
 */
const hasLink = (row: T): boolean =>
    row.href || props.linkField || props.linkFn ? true : false;

/**
 * Get the link that the row should redirect to.
 */
const getLinkUrl = (row: T): string | undefined => {
    if (props.linkField) {
        return row[props.linkField];
    }

    if (props.linkFn) {
        return props.linkFn(row);
    }

    return row.href;
};

/**
 * Handle the rows click event.
 */
const handleClick = (event: MouseEvent, row: T): void => {
    let linkUrl = getLinkUrl(row);
    emit("row-clicked");

    if (linkUrl) {
        handleLinkClick(event, linkUrl);
    }
};
</script>
