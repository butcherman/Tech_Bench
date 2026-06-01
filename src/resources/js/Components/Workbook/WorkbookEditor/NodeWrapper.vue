<script setup lang="ts">
import GridWrapper from "./Nodes/GridWrapper.vue";
import UnknownNode from "./Nodes/UnknownNode.vue";
import { computed } from "vue";

const props = defineProps<{
    node: workbookNode;
}>();

const component = computed(() => {
    switch (props.node.type) {
        case "grid-wrapper":
        case "wrapper":
            return GridWrapper;
    }

    return UnknownNode;
});
</script>

<template>
    <div>
        <component
            v-bind="node.props"
            :is="component"
            :contents="node.contents"
            :index="node.index"
        />
    </div>
</template>
