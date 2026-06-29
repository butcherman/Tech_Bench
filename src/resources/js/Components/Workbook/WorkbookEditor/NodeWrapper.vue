<script setup lang="ts">
import { computed } from "vue";
import DataTableEditor from "./Nodes/DataTableEditor.vue";
import FieldsetWrapperEditor from "./Nodes/FieldsetWrapperEditor.vue";
import GridWrapperEditor from "./Nodes/GridWrapperEditor.vue";
import InputNodeEditor from "./Nodes/InputNodeEditor.vue";
import StaticNodeEditor from "./Nodes/StaticNodeEditor.vue";
import TextNodeEditor from "./Nodes/TextNodeEditor.vue";
import UnknownNode from "../WorkbookNodes/UnknownNode.vue";

const props = defineProps<{
    node: workbookNode;
}>();

const component = computed(() => {
    switch (props.node.type) {
        case "grid-wrapper":
        case "wrapper":
            return GridWrapperEditor;
        case "fieldset":
            return FieldsetWrapperEditor;
        case "static":
            return StaticNodeEditor;
        case "text":
            return TextNodeEditor;
        case "data-table":
            return DataTableEditor;
        case "input":
            return InputNodeEditor;
        default:
            return UnknownNode;
    }
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
