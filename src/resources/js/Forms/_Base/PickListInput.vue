<script setup lang="ts" generic="T">
import { Message } from "primevue";
import { ref, toRef, computed, onMounted } from "vue";
import { useField } from "vee-validate";
import type { Ref } from "vue";

export interface listBox {
    label: string;
    value: string;
}

const props = defineProps<{
    id: string;
    list: T[];
    name: string;
    availableText?: string;
    help?: string;
    label?: string;
    labelField?: keyof T;
    selectedText?: string;
    size?: number;
    valueField?: keyof T;
}>();

// If the component is currently in focus
const hasFocus = ref<boolean>(false);

/**
 * Visible options in the select boxes
 */
const availableList = ref<T[]>([]) as Ref<T[]>;
const selectedList = ref<T[]>([]) as Ref<T[]>;

/**
 * Highlighted items in each of the select boxes
 */
const availableHighlighted = ref<T[]>([]) as Ref<T[]>;
const selectedHighlighted = ref<T[]>([]) as Ref<T[]>;

/*
|-------------------------------------------------------------------------------
| Key and Value Data
|-------------------------------------------------------------------------------
*/

/**
 * Key names of Label Field and Value Fields
 */
const selectLabelKey = computed<keyof T>(
    () => props.labelField ?? ("label" as keyof T)
);
const selectValueKey = computed<keyof T>(
    () => props.valueField ?? ("value" as keyof T)
);

/**
 * Get the value for the item that is being pushed to the value field
 */
const getOptionValue = (option: T): T | T[keyof T] => {
    // If the item is a string, it is the value
    if (typeof option === "string") {
        return option;
    }

    return option[selectValueKey.value];
};

/*
|-------------------------------------------------------------------------------
| Add and Remove Items from the List.
|-------------------------------------------------------------------------------
*/

/**
 * Add one or more items to the value & selected list
 */
const addItems = (init: boolean = false): void => {
    availableHighlighted.value.forEach((item: T) => {
        // Add to selected list, remove from available list
        selectedList.value.push(item);
        availableList.value.splice(availableList.value.indexOf(item), 1);

        // Add field to the value property
        if (!init) {
            value.value.push(getOptionValue(item));
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

/*
|-------------------------------------------------------------------------------
| Vee Validate
|-------------------------------------------------------------------------------
*/
const nameRef = toRef(props, "name");
const {
    errorMessage,
    value,
}: {
    errorMessage: Ref<string | undefined, string | undefined>;
    value: Ref<any[]>;
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
                    class="w-full border rounded-lg overflow-auto p-2 focus:outline-green-300"
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
            <div class="basis-2/12 flex justify-center text-muted">
                <div class="flex flex-col justify-center">
                    <button
                        type="button"
                        class="px-2 py-1 my-1 bg-slate-200 rounded-lg border border-slate-200"
                        @click="addAllItems()"
                    >
                        <fa-icon icon="angles-right" />
                    </button>
                    <button
                        type="button"
                        class="px-2 py-1 my-1 bg-slate-200 rounded-lg border border-slate-200"
                        @click="addItems()"
                    >
                        <fa-icon icon="angle-right" />
                    </button>

                    <button
                        type="button"
                        class="px-2 py-1 my-1 bg-slate-200 rounded-lg border border-slate-200"
                        @click="removeItems()"
                    >
                        <fa-icon icon="angle-left" />
                    </button>

                    <button
                        type="button"
                        class="px-2 py-1 my-1 bg-slate-200 rounded-lg border border-slate-200"
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
                    class="w-full border rounded-lg overflow-auto p-2 focus:outline-green-300"
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
