<script setup lang="ts">
import CanvasNodeOptions from "./CanvasNodeOptions.vue";

//

const props = defineProps<{
    node: workbookNode;
    nodeWrapper: workbookNode[];
}>();
</script>

<template>
    <div class="relative py-1 group/data">
        <CanvasNodeOptions
            :can-edit="true"
            class="hidden group-hover/data:flex"
        />
        <h1>Node</h1>
    </div>
</template>
