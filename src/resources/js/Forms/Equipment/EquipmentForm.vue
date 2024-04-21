<template>
    <VueForm
        ref="form"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="submitRoute"
        :submit-method="submitMethod"
        :submit-text="submitText"
    >
        <SelectInput
            id="equip-category"
            name="cat_id"
            label="Equipment Category"
            :list="categoryList"
            text-field="name"
            value-field="cat_id"
        />

        <TextInput id="equip-name" name="name" label="Equipment Name" />
        <fieldset>
            <legend>
                Customer Information to Gather<small>(drag to re-order)</small>
            </legend>
            <TextFieldArray
                name="custData"
                placeholder="Information to gather for customer"
                :datalist="dataList"
                :remove-warning="removeWarning"
                drag
            />
        </fieldset>
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import SelectInput from "../_Base/SelectInput.vue";
import TextFieldArray from "../_Base/TextFieldArray.vue";
import { computed } from "vue";
import { array, number, object, string } from "yup";

const props = defineProps<{
    categoryList: categoryList[];
    dataList: string[];
    equipment?: equipment;
}>();

const removeWarning = computed(() =>
    props.equipment
        ? "Removing this item will also remove any customer data gathered for this item"
        : undefined
);

const submitRoute = computed(() =>
    props.equipment
        ? route("equipment.update", props.equipment.equip_id)
        : route("equipment.store")
);
const submitMethod = computed(() => (props.equipment ? "put" : "post"));
const submitText = computed(() =>
    props.equipment ? "Update Equipment" : "Create New Equipment"
);

const initValues = {
    cat_id: props.equipment?.cat_id || null,
    name: props.equipment?.name || null,
    custData: props.equipment?.data_field_type?.map((obj) => obj.name) || [
        null,
        null,
        null,
    ],
};
const schema = object({
    cat_id: number().required().label("Category"),
    name: string().required(),
    custData: array().nullable(),
});
</script>

<style scoped lang="scss">
fieldset {
    border: 1px solid rgb(165, 163, 163, 0.5);
    margin: 5px;
    padding: 10px;
    legend {
        font-size: 1.2em;
        small {
            font-size: 0.7em;
        }
    }
}
</style>
