<script setup lang="ts">
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import PhoneFieldInput from "./PhoneFieldInput.vue";
import verifyModal from "@/Modules/verifyModal";
import { useFieldArray } from "vee-validate";

const props = defineProps<{
    id: string;
    name: string;
    phoneTypes: phoneType[];
    label?: string;
    removeWarning?: string;
}>();

const defaultRow = {
    phone: "",
    type: props.phoneTypes[0].description,
    ext: "",
};

/*
|-------------------------------------------------------------------------------
| Add or remove data rows
|-------------------------------------------------------------------------------
*/
const addRow = (): void => {
    push(defaultRow);
};

const removeRow = (rowIndex: number): void => {
    if (props.removeWarning) {
        verifyModal(props.removeWarning, "Warning").then((res) => {
            if (res) {
                remove(rowIndex);
            }
        });

        return;
    }

    remove(rowIndex);
};

/*
|-------------------------------------------------------------------------------
| Vee Validate
|-------------------------------------------------------------------------------
*/
const { remove, push, fields } = useFieldArray(props.name);
</script>

<template>
    <div>
        <fieldset :id="id">
            <legend class="font-bold text-muted block">
                {{ label }}
            </legend>
            <template v-for="(field, index) in fields" :key="field.key">
                <div class="flex">
                    <PhoneFieldInput
                        :id="`ph-input-${field.key}`"
                        :name="`${name}[${index}]`"
                        :phone-types="phoneTypes"
                        class="grow"
                    />
                    <div
                        class="flex flex-col justify-center ms-2 text-danger pointer"
                        v-tooltip="'Remove Row'"
                        @click="removeRow(index)"
                    >
                        <fa-icon icon="circle-xmark" />
                    </div>
                </div>
            </template>
        </fieldset>
        <div class="flex flex-row-reverse">
            <AddButton text="Add Row" size="small" pill @click="addRow" />
        </div>
    </div>
</template>
