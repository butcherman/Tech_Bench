<script setup lang="ts">
import NodeWrapper from "./NodeWrapper.vue";

const props = defineProps<{
    class: string;
    contents: workbookNode[];
    index: string;
    text: string | null;
}>();
</script>

<template>
    <fieldset :class="class">
        <legend>{{ text }}</legend>
        <template v-for="element in contents">
            <NodeWrapper :node="element" />
        </template>
    </fieldset>
</template>
