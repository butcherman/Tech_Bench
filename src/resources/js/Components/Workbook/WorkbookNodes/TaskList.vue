<script setup lang="ts">
import { onMounted, ref, watch } from "vue";
import {
    isPagePublic,
    saveWorkbookValue,
    taskListInitComplete,
    taskLists,
    whoAmI,
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
        completed_by: whoAmI.value ?? "unknown",
        value_type: "task-list-item",
    });

    item.completed = isComplete ? "now" : null;
    item.completed_by = "me";
};

watch(taskListInitComplete, (check) => {
    if (check) {
        initTaskList();
    }
});

onMounted(() => {
    Echo.channel(`workbook-task-list.${props.index}`).listen(
        ".WorkbookTaskListItemUpdated",
        (updatedModel: workbookTaskListEvent) => {
            let modifiedList = taskLists.value.find(
                (lst) => lst.list_index === props.index,
            );

            if (modifiedList) {
                let modifiedItem = modifiedList.workbook_task_list_item?.find(
                    (item) => item.list_item === updatedModel.model.list_item,
                );

                if (modifiedItem) {
                    modifiedItem.completed = updatedModel.model.completed;
                    modifiedItem.completed_by = updatedModel.model.completed_by;
                }
            }
        },
    );
});
</script>

<template>
    <div>
        <h3 class="text-center">{{ title }}</h3>
        <div>
            <template v-for="item in thisTaskList" :key="item">
                <div class="flex gap-2 bg-sky-300 my-2 p-2 rounded-lg">
                    <input
                        type="checkbox"
                        :checked="item.completed ? true : false"
                        @change="updateTaskItem($event, item)"
                    />
                    <div
                        class="text-lg grow"
                        :class="{ 'line-through': item.completed }"
                    >
                        {{ item.list_item }}
                    </div>
                    <div v-if="item.completed !== null" class="text-slate-500">
                        Completed {{ item.completed }} by
                        {{ item.completed_by }}
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>
