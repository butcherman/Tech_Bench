<script setup lang="ts">
import NodeOptions from "../NodeOptions.vue";
import StaticNode from "../../WorkbookNodes/StaticNode.vue";

const props = defineProps<{
    class: string;
    text: string;
    tag: string;
    index: string;
}>();
</script>

<template>
    <div class="relative group/static">
        <NodeOptions
            class="hidden group-hover/static:flex"
            :can-edit="false"
            :node-index="index"
        />
        <StaticNode v-bind="props" />
    </div>
</template>
