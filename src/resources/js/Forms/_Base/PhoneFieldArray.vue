<template>
    <div>
        <div
            v-for="(field, index) in fields"
            :key="field.key"
            class="row my-0 py-0"
        >
            <div class="col-sm-3 px-1">
                <SelectInput
                    :id="`type-${index}`"
                    :name="`${name}[${index}].type`"
                    :list="phoneTypes"
                />
            </div>
            <div class="col-sm-5 px-1">
                <PhoneInput
                    :id="`number-${index}`"
                    :name="`${name}[${index}].number`"
                />
            </div>
            <div class="col-sm-4 px-1">
                <div class="row p-0 m-0">
                    <div class="col-10">
                        <TextInput
                            :id="`ext-${index}`"
                            :name="`${name}[${index}].ext`"
                            placeholder="Ext"
                        />
                    </div>
                    <div class="col p-0 m-1">
                        <span
                            class="text-danger pointer"
                            title="Remove Row"
                            v-tooltip
                            @click="remove(index)"
                        >
                            <fa-icon icon="circle-xmark" />
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <AddButton class="float-end" small pill @click="addPhone" />
        </div>
    </div>
</template>

<script setup lang="ts">
import TextInput from "./TextInput.vue";
import PhoneInput from "./PhoneInput.vue";
import SelectInput from "./SelectInput.vue";
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import { useFieldArray } from "vee-validate";

const props = defineProps<{
    name: string;
    label?: string;
    placeholder?: string;
    drag?: boolean;
    datalist?: string[];
    removeWarning?: string;
    phoneTypes: string[];
}>();

const { remove, push, fields } = useFieldArray(props.name);

const addPhone = () => {
    push({
        type: "Mobile",
        number: "",
        ext: "",
    });
};
</script>
