<script setup lang="ts">
import { ref, watch } from "vue";
import {
    isPagePublic,
    saveWorkbookValue,
    taskListInitComplete,
    taskLists,
} from "@/Composables/Workbook/CustomerWorkbook.module";

const props = defineProps<{
    index: string;
    allowAddRow: boolean;
    allowDeleteRow: boolean;
    centerList: boolean;
    defaultList: string[];
    title: string;
}>();

const thisTaskList = ref<workbookTaskListEntry[]>([]);

/**
 * Initialize and populate the task list
 */
const initTaskList = () => {
    let list: workbookTaskList | undefined = taskLists.value.find(
        (lst) => lst.list_index === props.index,
    );

    if (!list || !list.workbook_task_list_item?.length) {
        buildTaskList();
        return;
    }

    thisTaskList.value = list.workbook_task_list_item;
};

/**
 * Build a blank task list
 */
const buildTaskList = () => {
    let listItems: workbookTaskListEntry[] = [];
    let index = 0;
    props.defaultList.forEach((item) => {
        listItems.push({
            list_item: item,
            order: index,
            completed: null,
            completed_by: null,
        });
        index++;
    });

    thisTaskList.value = listItems;

    // Save the new list to the database
    saveWorkbookValue({
        list_index: props.index,
        public: isPagePublic.value,
        locked: false,
        value_type: "task-list",
        workbook_task_list_item: listItems,
    });
};

/**
 * Update the completed status of a list item
 */
const updateTaskItem = (event: Event, item: workbookTaskListEntry) => {
    console.log("update", item, event);

    let target = event.target as HTMLInputElement;
    let isComplete: boolean = target.checked;

    saveWorkbookValue({
        list_index: props.index,
        list_item: item.list_item,
        order: item.order,
        completed: isComplete,
        completed_by: "misc",
        value_type: "task-list-item",
    });
};

watch(taskListInitComplete, (check) => {
    if (check) {
        initTaskList();
    }
});
</script>

<template>
    <div>
        <h3 class="text-center">{{ title }}</h3>
        <div>
            <template v-for="item in thisTaskList" :key="item">
                <div class="bg-sky-300 my-2 p-2 rounded-lg">
                    <input
                        type="checkbox"
                        @change="updateTaskItem($event, item)"
                    />
                    {{ item }}
                </div>
            </template>
        </div>
    </div>
</template>
