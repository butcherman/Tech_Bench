<script setup lang="ts">
import DataTableEditor from "./Nodes/DataTableEditor.vue";
import FieldsetWrapperEditor from "./Nodes/FieldsetWrapperEditor.vue";
import GridWrapperEditor from "./Nodes/GridWrapperEditor.vue";
import InputNodeEditor from "./Nodes/InputNodeEditor.vue";
import StaticNodeEditor from "./Nodes/StaticNodeEditor.vue";
import TaskListEditor from "./Nodes/TaskListEditor.vue";
import TextNodeEditor from "./Nodes/TextNodeEditor.vue";
import UnknownNode from "../WorkbookNodes/UnknownNode.vue";
import { computed } from "vue";

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
        case "header":
            return TextNodeEditor;
        case "data-table":
            return DataTableEditor;
        case "input":
            return InputNodeEditor;
        case "task-list":
            return TaskListEditor;
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
