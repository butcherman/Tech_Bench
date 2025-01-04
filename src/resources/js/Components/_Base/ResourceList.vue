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

<script setup lang="ts">
import { handleLinkClick } from "@/Composables/links.module";

const emit = defineEmits(["row-clicked"]);
const props = defineProps<{
    list: any[];
    center?: boolean;
    noBorder?: boolean;
    emptyText?: string;
    textField?: string;
    linkField?: string;
    linkFn?: (row: any) => string;
}>();

const hasLink = (row) => row.href || props.linkField || props.linkFn;

const handleClick = (event, item) => {
    console.log("row clicked");
    console.log(event, item);

    if (hasLink(item)) {
        let linkUrl = item.href || item[props.linkField] || props.linkFn(item);

        handleLinkClick(event, linkUrl);
    }
};
</script>
