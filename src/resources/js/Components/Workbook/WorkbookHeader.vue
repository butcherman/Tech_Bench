<script setup lang="ts">
import NodeWrapper from "./WorkbookNodes/NodeWrapper.vue";

const props = defineProps<{
    headerSkeleton: workbookNode[];
}>();
</script>

<template>
    <div v-if="headerSkeleton.length">
        <template v-for="node in headerSkeleton" :key="node.index">
            <NodeWrapper :node="node" />
        </template>
    </div>
</template>
