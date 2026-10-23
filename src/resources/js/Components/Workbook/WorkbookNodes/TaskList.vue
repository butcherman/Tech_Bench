<script setup lang="ts">
import { InputText } from "primevue";
import { onMounted, onUnmounted, ref, watch } from "vue";
import {
    isPagePublic,
    isPreviewMode,
    saveWorkbookValue,
    taskListInitComplete,
    taskLists,
    whoAmI,
} from "@/Composables/Workbook/CustomerWorkbook.module";
import BaseBadge from "@/Components/_Base/Badges/BaseBadge.vue";

const props = defineProps<{
    index: string;
    allowAddRow: boolean;
    allowDeleteRow: boolean;
    centerList: boolean;
    defaultList: string[];
    title: string;
}>();

const thisTaskList = ref<workbookTaskListEntry[]>([]);
const newTask = ref<string>();

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
    if (!isPreviewMode.value) {
        saveWorkbookValue({
            list_index: props.index,
            public: isPagePublic.value,
            locked: false,
            value_type: "task-list",
            workbook_task_list_item: listItems,
        });
    }
};

/**
 * Update the completed status of a list item
 */
const updateTaskItem = (event: Event, item: workbookTaskListEntry) => {
    let target = event.target as HTMLInputElement;
    let isComplete: boolean = target.checked;

    saveWorkbookValue({
        list_index: props.index,
        list_item: item.list_item,
        order: item.order,
        completed: isComplete,
        completed_by: whoAmI.value ?? "unknown",
        value_type: "task-list-item",
        public: isPagePublic.value,
    });

    item.completed = isComplete ? "now" : null;
    item.completed_by = "me";
};

/**
 * Add a new Task List item
 */
const addItem = () => {
    if (isPreviewMode.value) {
        alert("Preview Mode - Use Item Form to add or remove items");
    }

    if (newTask.value?.length) {
        let newItem = {
            list_index: props.index,
            list_item: newTask.value,
            order: thisTaskList.value.length,
            completed: null,
            completed_by: null,
            value_type: "task-list-item",
            public: isPagePublic.value,
        };

        saveWorkbookValue(newItem);
        thisTaskList.value.push(newItem);

        newTask.value = undefined;
    }
};

/**
 * Delete a task list item
 */
const deleteItem = (item: workbookTaskListEntry) => {
    if (isPreviewMode.value) {
        alert("Preview Mode - Use Item Form to add or remove items");
    }

    saveWorkbookValue({
        list_index: props.index,
        list_item: item.list_item,
        order: item.order,
        completed_by: whoAmI.value ?? "unknown",
        value_type: "task-list-item",
        public: isPagePublic.value,
        delete_item: true,
    });

    let index = thisTaskList.value.findIndex((listItem) => listItem === item);
    thisTaskList.value.splice(index, 1);
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

    if (isPreviewMode.value) {
        initTaskList();
    }
});

onUnmounted(() => (taskListInitComplete.value = false));
</script>

<template>
    <div>
        <h3 class="text-center">{{ title }}</h3>
        <div>
            <template v-for="item in thisTaskList" :key="item">
                <div class="bg-sky-300 my-2 p-2 rounded-lg">
                    <div class="flex gap-2">
                        <input
                            type="checkbox"
                            :checked="item.completed ? true : false"
                            @change="updateTaskItem($event, item)"
                        />
                        <div
                            class="text-lg grow"
                            :class="{
                                'line-through': item.completed,
                                'text-center': centerList,
                            }"
                        >
                            {{ item.list_item }}
                        </div>
                        <div v-if="allowDeleteRow">
                            <span
                                class="text-danger pointer"
                                v-tooltip.left="'Delete Task'"
                                @click="deleteItem(item)"
                            >
                                <fa-icon icon="trash-alt" />
                            </span>
                        </div>
                    </div>
                    <div
                        v-if="item.completed !== null"
                        class="text-slate-500 flex flex-row-reverse"
                    >
                        <div class="text-xs">
                            Completed {{ item.completed }} by
                            {{ item.completed_by }}
                        </div>
                    </div>
                </div>
            </template>
            <div v-if="allowAddRow" class="relative w-full">
                <InputText
                    v-model="newTask"
                    class="w-full"
                    placeholder="Create New Task"
                    @keyup.enter="addItem"
                />
                <div class="absolute inset-y-0 right-1 flex items-center">
                    <BaseBadge
                        icon="check"
                        :variant="newTask?.length ? 'success' : 'light'"
                        @click="addItem"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
