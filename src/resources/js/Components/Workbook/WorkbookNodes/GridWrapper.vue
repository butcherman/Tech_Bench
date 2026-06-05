<script setup lang="ts">
import NodeWrapper from "./NodeWrapper.vue";

const props = defineProps<{
    class: string;
    contents: workbookNode[];
    index: string;
}>();
</script>

<template>
    <div :class="class">
        <div v-for="container in contents" :key="container.index">
            <template v-for="element in container.contents">
                <NodeWrapper :node="element" />
            </template>
        </div>
    </div>
</template>
