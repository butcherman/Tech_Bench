<script setup lang="ts">
import NodeOptions from "../NodeOptions.vue";
import TextNode from "../../WorkbookNodes/TextNode.vue";

const props = defineProps<{
    class: string;
    text: string;
    tag: string;
    index: string;
}>();
</script>

<template>
    <div class="relative group/text">
        <NodeOptions
            class="hidden group-hover/text:flex"
            :can-edit="true"
            :node-index="index"
        />
        <TextNode v-bind="props" />
    </div>
</template>
