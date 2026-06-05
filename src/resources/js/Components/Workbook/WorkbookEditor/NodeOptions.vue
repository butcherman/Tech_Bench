<script setup lang="ts">
import { isArray } from "lodash";
import {
    deleteNode,
    editNode,
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
const getParentNode = ():
    | workbookNode
    | workbookPage
    | workbookNode[]
    | false => {
    // Look in the Header and Footer sections first
    if (workbookData.header.some((child) => child.index === props.nodeIndex)) {
        return workbookData.header;
    }

    if (workbookData.footer.some((child) => child.index === props.nodeIndex)) {
        return workbookData.footer;
    }

    for (let page of workbookData.body) {
        let parent = searchContentForParent(page);
        if (parent) {
            return parent;
        }
    }

    return false;
};

/**
 * Perform a recursive/deep search for the node and return its parent array
 */
const searchContentForParent = (
    node: workbookNode | workbookPage,
): workbookNode | workbookPage | false => {
    // Perform surface level search
    if (
        node.contents &&
        node.contents.some((child) => {
            return child.index === props.nodeIndex;
        })
    ) {
        return node;
    }

    // Perform recursive deep search
    if (node.contents) {
        for (let section of node.contents) {
            if (section.contents) {
                let parent = searchContentForParent(section);
                if (parent) {
                    return parent;
                }
            }
        }
    }

    return false;
};

/**
 * Make a duplicate of the node being worked on and place it below.
 */
const duplicateNode = () => {
    const parent = getParentNode();

    if (parent) {
        let parentContents = isArray(parent) ? parent : parent.contents;
        let originalNode = parentContents?.find(
            (child) => child.index === props.nodeIndex,
        );

        if (originalNode) {
            let originalIndex = parentContents?.indexOf(originalNode);
            let newNode = getClonedNode(originalNode);

            if (originalIndex !== undefined) {
                parentContents?.splice(originalIndex, 0, newNode);
            }
        }
    }
};

/**
 * Edit the data of the current node being worked on
 */
const editNodeData = () => {
    const parent = getParentNode();

    if (parent) {
        let parentContents = isArray(parent) ? parent : parent.contents;
        let originalNode = parentContents?.find(
            (child) => child.index === props.nodeIndex,
        );

        if (originalNode) {
            editNode(originalNode);
        }
    }
};

/**
 * Find the Node and its parent being worked on and remove it from the Workbook.
 */
const removeNode = () => {
    const parent = getParentNode();

    if (parent) {
        let parentContents = isArray(parent) ? parent : parent.contents;
        let originalNode = parentContents?.find(
            (child) => child.index === props.nodeIndex,
        );

        if (originalNode && parentContents) {
            deleteNode(originalNode, parentContents);
        }
    }
};
</script>

<template>
    <div class="absolute end-0 -top-1 text-xs gap-1 z-999">
        <span
            v-if="canEdit"
            class="text-warning pointer"
            v-tooltip.bottom="'Edit Element Data'"
            @click="editNodeData"
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
