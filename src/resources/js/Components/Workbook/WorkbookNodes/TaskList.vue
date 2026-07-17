<script setup lang="ts">
import { taskLists } from "@/Composables/Workbook/CustomerWorkbook.module";
import { computed } from "vue";

const props = defineProps<{
    index: string;
    allowAddRow: boolean;
    allowDeleteRow: boolean;
    centerList: boolean;
    defaultList: string[];
    title: string;
}>();

const thisTaskList = computed(() =>
    taskLists.value.find((list) => list.list_index === props.index),
);
</script>

<template>
    <div>
        <h3 class="text-center">{{ title }}</h3>
        <div>
            <template
                v-for="item in thisTaskList?.workbook_task_list_item"
                :key="item"
            >
                <div class="bg-sky-300 my-2 p-2 rounded-lg">
                    <input type="checkbox" />
                    {{ item.list_item }}
                </div>
            </template>
        </div>
    </div>
</template>
