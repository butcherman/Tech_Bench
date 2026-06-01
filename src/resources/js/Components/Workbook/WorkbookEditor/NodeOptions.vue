<script setup lang="ts">
import {
    deleteNode,
    getClonedNode,
    workbookData,
} from "@/Composables/Workbook/Canvas/WorkbookEditor.module";

const props = defineProps<{
    canEdit: boolean;
    nodeIndex: string;
}>();

/**
 * Find the parent of the Node being worked on.
 */
const getParentNode = (): workbookPage | workbookNode | false => {
    for (const node of workbookData.body) {
        if (
            node.contents &&
            node.contents.some((child) => child.index === props.nodeIndex)
        ) {
            return node;
        }
    }

    for (const node of workbookData.header) {
        if (
            node.contents &&
            node.contents.some((child) => child.index === props.nodeIndex)
        ) {
            return node;
        }
    }

    for (const node of workbookData.footer) {
        if (
            node.contents &&
            node.contents.some((child) => child.index === props.nodeIndex)
        ) {
            return node;
        }
    }

    return false;
};

/**
 * Make a duplicate of the node being worked on and place it below.
 */
const duplicateNode = () => {
    const parent = getParentNode();

    if (parent && parent.contents) {
        const node = parent.contents?.find(
            (child) => child.index === props.nodeIndex,
        );

        let clonedIndex = parent.contents.indexOf(node);
        let newNode = getClonedNode(node);
        parent.contents.splice(clonedIndex, 0, newNode);
    }
};

/**
 * Find the Node and its parent being worked on and remove it from the Workbook.
 */
const removeNode = () => {
    const parent = getParentNode();

    if (parent && parent.contents) {
        const node = parent.contents?.find(
            (child) => child.index === props.nodeIndex,
        );

        deleteNode(node, parent.contents);
    }
};
</script>

<template>
    <div class="absolute end-0 -top-1 text-xs gap-1">
        <span
            v-if="canEdit"
            class="text-warning pointer"
            v-tooltip.bottom="'Edit Element Data'"
        >
            <fa-icon icon="pencil" />
        </span>
        <span
            class="text-primary pointer"
            v-tooltip.bottom="'Copy Element'"
            @click="duplicateNode"
        >
            <fa-icon icon="clone" />
        </span>
        <span
            class="text-danger pointer"
            v-tooltip.bottom="'Delete Element'"
            @click="removeNode"
        >
            <fa-icon icon="trash-alt" />
        </span>
    </div>
</template>
