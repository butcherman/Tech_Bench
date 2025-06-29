<script setup lang="ts">
import AutoCompleteInput from "./AutoCompleteInput.vue";
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import draggable from "vuedraggable";
import verifyModal from "@/Modules/verifyModal";
import { useFieldArray } from "vee-validate";
import { ref } from "vue";

type dragEvent = {
    oldIndex: number;
    newIndex: number;
} & Event;

const props = defineProps<{
    dataList: string[];
    name: string;
    draggable?: boolean;
    label?: string;
    placeholder?: string;
    removeWarning?: string;
}>();

/*
|-------------------------------------------------------------------------------
| Drag Events
|-------------------------------------------------------------------------------
*/
const drag = ref<boolean>(false);

const onDragStart = (): void => {
    drag.value = true;
};

const onDragEnd = (event: dragEvent): void => {
    drag.value = false;
    move(event.oldIndex, event.newIndex);
};

const removeWarning = (index: number): void => {
    if (props.removeWarning && fields.value[index].value) {
        verifyModal(props.removeWarning).then((res) => {
            if (res) {
                remove(index);
            }
        });
    } else {
        remove(index);
    }
};

/*
|-------------------------------------------------------------------------------
| Vee Validate
|-------------------------------------------------------------------------------
*/
const { remove, push, move, fields } = useFieldArray(props.name);
</script>

<template>
    <draggable
        :animation="200"
        :disabled="!draggable"
        :list="fields"
        item-key="index"
        @start="onDragStart"
        @end="onDragEnd"
    >
        <template #item="{ element, index }">
            <div class="px-2 pointer">
                <AutoCompleteInput
                    :id="`${name}-${element.key}`"
                    :name="`${name}[${index}]`"
                    :label="label"
                    :placeholder="placeholder"
                    :data-list="dataList"
                >
                    <template v-if="draggable" #start-text>
                        <span v-tooltip="'Drag to Re-Order'">
                            <fa-icon icon="sort" />
                        </span>
                    </template>
                    <template #end-text>
                        <span
                            class="pointer text-danger"
                            v-tooltip="'Remove this item'"
                            @click="removeWarning(index)"
                        >
                            <fa-icon icon="xmark" />
                        </span>
                    </template>
                </AutoCompleteInput>
            </div>
        </template>
        <template #footer>
            <AddButton class="float-end px-2! py-0!" pill @click="push('')" />
        </template>
    </draggable>
</template>
