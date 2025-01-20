<template>
    <draggable
        :disabled="!draggable"
        :list="fields"
        item-key="index"
        @end="onDragEnd"
    >
        <template #item="{ element, index }">
            <div class="px-2">
                <AutoCompleteInput
                    :id="`${name}-${element.key}`"
                    :name="`${name}[${index}]`"
                    :label="label"
                    :placeholder="placeholder"
                    :data-list="dataList"
                >
                    <template v-if="draggable" #start-text>
                        <span class="pointer" v-tooltip="'Drag to Re-Order'">
                            <fa-icon icon="sort" />
                        </span>
                    </template>
                    <template #end-text>
                        <span
                            class="pointer text-danger"
                            v-tooltip="'Remove this item'"
                            @click="remove(index)"
                        >
                            <fa-icon icon="xmark" />
                        </span>
                    </template>
                </AutoCompleteInput>
            </div>
        </template>
        <template #footer>
            <AddButton class="float-end !px-2 !py-0" pill @click="push('')" />
        </template>
    </draggable>
</template>

<script setup lang="ts">
import AutoCompleteInput from "./AutoCompleteInput.vue";
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import draggable from "vuedraggable";
import { useFieldArray } from "vee-validate";

const props = defineProps<{
    name: string;
    label?: string;
    placeholder?: string;
    draggable?: boolean;
    dataList: string[];
    removeWarning?: string;
}>();

const { remove, push, fields, move } = useFieldArray(props.name);

type dragEvent = {
    oldIndex: number;
    newIndex: number;
} & Event;

const onDragEnd = (event: dragEvent) => {
    move(event.oldIndex, event.newIndex);
};
</script>
