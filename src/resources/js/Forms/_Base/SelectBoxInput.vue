<template>
    <div class="mb-3">
        <label v-if="label" :for="id" class="form-label w-100">
            {{ label }}:
        </label>
        <div :id="id" class="row">
            <div class="col">
                <p>{{ availableText || "Available" }}:</p>
                <select
                    :id="`${id}-available-list`"
                    class="form-select"
                    :class="{ 'is-valid': isValid, 'is-invalid': isInvalid }"
                    :size="size || 10"
                    multiple
                >
                    <template v-for="option in availableList">
                        <template v-if="typeof option === 'string'">
                            <option :value="option" :title="option">
                                {{ option }}
                            </option>
                        </template>
                        <template v-else>
                            <option
                                :value="option[valueField]"
                                :title="option[textField]"
                            >
                                {{ option[textField] }}
                            </option>
                        </template>
                    </template>
                </select>
            </div>
            <div
                class="col-1 justify-content-center align-items-center d-flex flex-column"
            >
                <button
                    type="button"
                    class="btn btn-light my-2"
                    @click="addItems"
                >
                    <fa-icon icon="caret-right" />
                </button>
                <button
                    type="button"
                    class="btn btn-light my-2"
                    @click="removeItems"
                >
                    <fa-icon icon="caret-left" />
                </button>
            </div>
            <div class="col">
                <p>{{ selectedText || "Selected" }}:</p>
                <select
                    :id="`${id}-selected-list`"
                    class="form-select"
                    :class="{ 'is-valid': isValid, 'is-invalid': isInvalid }"
                    :size="size || 10"
                    multiple
                >
                    <template v-for="option in value">
                        <option :value="option" :title="getTextField(option)">
                            {{ getTextField(option) }}
                        </option>
                    </template>
                </select>
            </div>
        </div>
        <span
            v-if="errorMessage && (meta.dirty || meta.touched)"
            class="text-danger"
        >
            {{ errorMessage }}
        </span>
    </div>
</template>

<script setup lang="ts">
import { ref, toRef, computed, onMounted } from "vue";
import { useField } from "vee-validate";

onMounted(() => init());

const props = defineProps<{
    id: string;
    name: string;
    list: any[];
    label?: string;
    textField?: string;
    valueField?: string;
    size?: number;
    availableText?: string;
    selectedText?: string;
}>();

const textField = computed<string>(() =>
    props.textField ? props.textField : "text"
);
const valueField = computed<string>(() =>
    props.valueField ? props.valueField : "value"
);

/**
 * Lists for each of the Select Boxes
 */
const availableList = ref<any[]>([]);
const selectedList = ref<string[]>([]);

/**
 * If the list item is an array of objects, get the text for the text field
 */
const getTextField = (fieldValue: string) => {
    let fullField = findOriginalItem(fieldValue);

    if (typeof fullField === "string") {
        return fullField;
    }

    return fullField[textField.value];
};

/**
 * Move an item from the Available list to the Selected List
 */
const addItems = () => {
    let addingArray = findSelectedItems(`${props.id}-available-list`);

    // Move the selected items from Available to Selected
    addingArray.forEach((itemValue) => {
        selectedList.value.push(itemValue);

        let listItemIndx = availableList.value.findIndex((i) => i == itemValue);
        availableList.value.splice(listItemIndx, 1);
    });

    // Update VeeValidate with the new values
    handleChange(selectedList.value);
};

/**
 * Remove an item from the Selected list and over to the Available list
 */
const removeItems = () => {
    let removeArray = findSelectedItems(`${props.id}-selected-list`);

    // Move the selected items from Available to Selected
    removeArray.forEach((itemValue) => {
        availableList.value.push(findOriginalItem(itemValue));

        let listItemIndex = selectedList.value.findIndex((i) => i == itemValue);
        selectedList.value.splice(listItemIndex, 1);
    });

    // Update VeeValidate with the new values
    handleChange(selectedList.value);
};

/**
 * Find the original item in the list prop based on the Value text
 */
const findOriginalItem = (fieldValue: string) => {
    let fullField = props.list.find((item) => {
        if (typeof item === "string") {
            return item === fieldValue;
        } else {
            return item[valueField.value] == fieldValue;
        }
    });

    return fullField;
};

/**
 * Find selected items in one of the two select boxes
 */
const findSelectedItems = (selectId: string) => {
    let selectedOptions = (<HTMLSelectElement>document.getElementById(selectId))
        ?.options;

    let selectedArray = [];

    // Find which items are selected
    for (let i = 0; i < selectedOptions.length; i++) {
        if (selectedOptions[i].selected) {
            selectedArray.push(selectedOptions[i].value);
        }
    }

    return selectedArray;
};

/**
 * Initialize the Select Box by removing selected items from the available list
 */
const init = () => {
    if (value.value) {
        props.list.forEach((listItem) => {
            /**
             * If the list is an array of strings, process them
             */
            if (typeof listItem === "string") {
                let isFound = (<string[]>value.value).find(
                    (item) => item === listItem
                );

                if (isFound) {
                    selectedList.value.push(listItem);
                } else {
                    availableList.value.push(listItem);
                }
            } else {
                /**
                 * If the list is an array of objects, process based on text and value fields
                 */
                let isFound = (<string[]>value.value).find(
                    (item) => item === listItem[valueField.value]
                );

                if (isFound) {
                    selectedList.value.push(listItem[valueField.value]);
                } else {
                    availableList.value.push(listItem);
                }
            }
        });
    } else {
        props.list.forEach((item) => availableList.value.push(item));
    }
};

const isValid = computed<boolean>(() => {
    return meta.valid && meta.validated && !meta.pending;
});

const isInvalid = computed<boolean>(() => {
    return !meta.valid && meta.validated && !meta.pending;
});

const nameRef = toRef(props, "name");
const { errorMessage, value, meta, handleChange } = useField(nameRef);
</script>
