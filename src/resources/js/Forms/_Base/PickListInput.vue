<script setup lang="ts">
import { Message } from "primevue";
import { ref, toRef, computed, onMounted } from "vue";
import { useField } from "vee-validate";
import type { Ref } from "vue";

export interface listBox {
    label: string;
    value: string;
    [key: string]: string;
}

const props = defineProps<{
    id: string;
    list: listBox[];
    name: string;
    availableText?: string;
    help?: string;
    label?: string;
    labelField?: string;
    selectedText?: string;
    size?: number;
    valueField?: string;
}>();

const selectLabelKey = computed<string>(() => props.labelField ?? "label");
const selectValueKey = computed<string>(() => props.valueField ?? "value");

// If the component is currently in focus
const hasFocus = ref<boolean>(false);

// Visible options in the select boxes
const availableList = ref<listBox[]>([]);
const selectedList = ref<listBox[]>([]);

// Highlighted items in each of the select boxes
const availableHighlighted = ref<listBox[]>([]);
const selectedHighlighted = ref<listBox[]>([]);

/**
 * Add one or more items to the value & selected list
 */
const addItems = (init = false): void => {
    availableHighlighted.value.forEach((item: listBox) => {
        // Add to selected list, remove from available list
        selectedList.value.push(item);
        availableList.value.splice(availableList.value.indexOf(item), 1);

        // Add field to the value property
        if (!init) {
            let val = item[selectValueKey.value] ?? item;
            value.value.push(val);
        }
    });

    availableHighlighted.value = [];
};

/**
 * Add All items to the value & selected list
 */
const addAllItems = (): void => {
    availableHighlighted.value = [];
    availableList.value.forEach((item) => {
        availableHighlighted.value.push(item);
    });

    addItems();
};

/**
 * Remove one or more items from the value & selected list
 */
const removeItems = (): void => {
    selectedHighlighted.value.forEach((item) => {
        // Add to available list, remove from selected list
        availableList.value.push(item);
        selectedList.value.splice(selectedList.value.indexOf(item), 1);

        // Remove the item from the value property
        value.value.splice(
            value.value.indexOf(item[selectValueKey.value] ?? item),
            1
        );
    });

    selectedHighlighted.value = [];
};

/**
 * Remove all items from the value & selected list
 */
const removeAllItems = (): void => {
    selectedHighlighted.value = [];
    selectedList.value.forEach((item) => {
        selectedHighlighted.value.push(item);
    });
    removeItems();
};

/*
|-------------------------------------------------------------------------------
| When this component is mounted, we must separate the list of items available
| vs the items selected.
|-------------------------------------------------------------------------------
*/
onMounted(() => {
    availableList.value = [...props.list];

    if (value.value) {
        availableList.value.forEach((listItem) => {
            let val = listItem[selectValueKey.value] ?? listItem;

            if (value.value.includes(val)) {
                availableHighlighted.value.push(listItem);
            }
        });

        addItems(true);
    } else {
        value.value = [];
    }
});

const nameRef = toRef(props, "name");
const {
    errorMessage,
    value,
}: {
    errorMessage: Ref<string | undefined, string | undefined>;
    value: Ref<string[]>;
} = useField(nameRef);
</script>

<template>
    <div class="w-full">
        <label v-if="label" :for="id" class="w-full text-muted font-bold block">
            {{ label }}
        </label>
        <div :id="id" class="flex flex-row justify-center">
            <div class="basis-5/12">
                <div class="text-muted">
                    {{ availableText ?? "Available" }}
                </div>
                <select
                    v-model="availableHighlighted"
                    class="w-full border rounded-lg overflow-auto p-2"
                    :id="`${id}-available-list`"
                    :size="size ?? 10"
                    multiple
                    @focus="hasFocus = true"
                    @blur="hasFocus = false"
                >
                    <template v-for="option in availableList">
                        <option :value="option">
                            {{
                                typeof option === "string"
                                    ? option
                                    : option[selectLabelKey]
                            }}
                        </option>
                    </template>
                </select>
            </div>
            <div class="basis-2/12 flex justify-center">
                <div class="flex flex-col justify-center">
                    <button
                        type="button"
                        class="move-button"
                        @click="addAllItems()"
                    >
                        <fa-icon icon="angles-right" />
                    </button>
                    <button
                        type="button"
                        class="move-button"
                        @click="addItems()"
                    >
                        <fa-icon icon="angle-right" />
                    </button>

                    <button
                        type="button"
                        class="move-button"
                        @click="removeItems()"
                    >
                        <fa-icon icon="angle-left" />
                    </button>

                    <button
                        type="button"
                        class="move-button"
                        @click="removeAllItems()"
                    >
                        <fa-icon icon="angles-left" />
                    </button>
                </div>
            </div>
            <div class="basis-5/12">
                <div class="text-muted">
                    {{ selectedText ?? "Selected" }}
                </div>
                <select
                    v-model="selectedHighlighted"
                    class="w-full border rounded-lg overflow-auto p-2"
                    :id="`${id}-selected-list`"
                    :size="size ?? 10"
                    multiple
                    @focus="hasFocus = true"
                    @blur="hasFocus = false"
                >
                    <template v-for="option in selectedList">
                        <option :value="option">
                            {{
                                typeof option === "string"
                                    ? option
                                    : option[selectLabelKey]
                            }}
                        </option>
                    </template>
                </select>
            </div>
        </div>
        <Message size="small" severity="error" variant="simple">
            {{ errorMessage }}
        </Message>
        <Message
            v-if="hasFocus"
            size="small"
            severity="secondary"
            variant="simple"
        >
            {{ help }}
        </Message>
    </div>
</template>

<style scoped lang="postcss">
.move-button {
    @apply px-2 py-1 my-2 bg-slate-200 rounded-lg border;
}
</style>
