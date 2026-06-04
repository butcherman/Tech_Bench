<script setup lang="ts">
import FieldsetWrapper from "./Nodes/FieldsetWrapper.vue";
import GridWrapper from "./Nodes/GridWrapper.vue";
import StaticNode from "./Nodes/StaticNode.vue";
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
        case "fieldset":
            return FieldsetWrapper;
        case "static":
            return StaticNode;
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
