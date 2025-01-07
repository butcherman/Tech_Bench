<template>
    <div>
        <div
            v-for="(field, index) in fields"
            :key="field.key"
            class="flex flex-row"
        >
            <SelectInput
                :id="`type-${index}`"
                :name="`${name}[${index}].type`"
                :list="phoneTypes"
                class="basis-1/6 md:basis-1/5 md:px-1"
            />
            <PhoneInput
                :id="`${name}-${field.key}`"
                :name="`${name}[${index}].number`"
                :label="label"
                :placeholder="placeholder"
                :datalist="datalist"
                class="grow"
            />
            <TextInput
                :id="`ext-${index}`"
                :name="`${name}[${index}].ext`"
                placeholder="Ext"
                class="basis-1/5 md:px-1"
            />
            <div class="flex ms-2">
                <button
                    type="button"
                    class="text-danger"
                    v-tooltip="'Remove Row'"
                    @click="remove(index)"
                >
                    <fa-icon icon="xmark" />
                </button>
            </div>
        </div>
        <AddButton class="float-end !px-2 !py-0" pill @click="addPhone" />
    </div>
</template>

<script setup lang="ts">
import TextInput from "./TextInput.vue";
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import { useFieldArray } from "vee-validate";
import SelectInput from "./SelectInput.vue";
import PhoneInput from "./PhoneInput.vue";

const props = defineProps<{
    name: string;
    label?: string;
    placeholder?: string;
    drag?: boolean;
    datalist?: string[];
    removeWarning?: string;
    phoneTypes: { text: string; value: string }[];
}>();

const addPhone = () => {
    push({
        type: props.phoneTypes[0].value,
        number: "",
        ext: "",
    });
};

const { remove, push, fields } = useFieldArray(props.name);
</script>
