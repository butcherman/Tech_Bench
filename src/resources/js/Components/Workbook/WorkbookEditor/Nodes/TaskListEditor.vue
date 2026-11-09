<script setup lang="ts">
import NodeOptions from "../NodeOptions.vue";
import TaskList from "../../WorkbookNodes/TaskList.vue";

const props = defineProps<{
    index: string;
    allowAddRow: boolean;
    allowDeleteRow: boolean;
    centerList: boolean;
    defaultList: string[];
    title: string;
}>();
</script>

<template>
    <div class="relative group/task-list">
        <NodeOptions
            class="hidden group-hover/task-list:flex"
            :can-edit="true"
            :node-index="index"
        />
        <TaskList v-bind="props" />
    </div>
</template>
