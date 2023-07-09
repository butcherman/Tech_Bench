<template>
    <draggable :disabled="!drag" :list="fields" item-key="index" @end="onDragEnd">
        <template #item="{ element, index }">
            <div>
                <TextInput
                    :id="`${name}-${element.key}`"
                    :name="`${name}[${index}]`"
                    :label="label"
                    :placeholder="placeholder"
                    :datalist="datalist"
                >
                    <template v-if="drag" #start-group-text>
                        <span
                            class="input-group-text pointer"
                            title="Drag to Re-Order"
                            v-tooltip
                        >
                            <fa-icon icon="sort" />
                        </span>
                    </template>
                    <template #end-group-text>
                        <span
                            class="input-group-text pointer"
                            title="Remove this item"
                            v-tooltip
                            @click="verifyRemove(index)"
                        >
                            <fa-icon icon="xmark" class="text-danger" />
                        </span>
                    </template>
                </TextInput>
            </div>
        </template>
        <template #footer>
            <AddButton class="btn-pill btn-sm float-end" @click="push('')" />
        </template>
    </draggable>
</template>

<script setup lang="ts">
import TextInput from "./TextInput.vue";
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import draggable from "vuedraggable";
import { useFieldArray } from "vee-validate";
import  verify  from '@/Modules/verify';

const props = defineProps<{
    name: string;
    label?: string;
    placeholder?: string;
    drag?: boolean;
    datalist?: string[];
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

/**
 * Present a warning message to the user about removing the specified item
 */
const verifyRemove = (index: number) => {
    if(!props.removeWarning) {
        remove(index);
    } else {
        console.log('remove me');
        verify({ title: 'WARNING:  Possible Data Loss', message: props.removeWarning }).then((res) => {
            if(res) {
                remove(index);
            }
        });
    }
}
</script>
