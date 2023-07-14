<template>
    <VueForm
        ref="equipmentForm"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-text="submitText"
        @submit="onSubmit"
    >
        <SelectInput
            id="equip-category"
            name="cat_id"
            label="Equipment Category"
            :list="categories"
            text-field="name"
            value-field="cat_id"
        />

        <TextInput id="equip-name" name="name" label="Equipment Name" />
        <fieldset>
            <legend>
                Customer Information to Gather
                <small>(drag to re-order)</small>
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
import { useForm } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import { object, string, number, array } from "yup";

const props = defineProps<{
    categories: categoryList[];
    dataList: string[];
    equipment?: equipWithData;
}>();

const submitText = computed(() =>
    props.equipment ? "Update Equipment" : "Create Equipment"
);
const removeWarning = computed(() =>
    props.equipment
        ? "Removing this item will also remove any customer data gathered for this item"
        : undefined
);

const equipmentForm = ref<InstanceType<typeof VueForm> | null>(null);
const initValues = {
    cat_id: props.equipment ? props.equipment.cat_id : null,
    name: props.equipment ? props.equipment.name : "",
    custData: props.equipment
        ? props.equipment.data_field_type.map((obj) => obj.name)
        : [null, null, null],
};
const schema = object({
    cat_id: number().required().label("Category"),
    name: string().required(),
    custData: array().ensure().min(1, "You must have at least one entry"),
});

type equipForm = {
    cat_id: number;
    name: string;
    custData: string[];
};

const onSubmit = (form: equipForm) => {
    const formData = useForm(form);

    if (props.equipment) {
        formData.put(route("equipment.update", props.equipment.equip_id), {
            onFinish: () => equipmentForm.value?.endSubmit(),
        });
    } else {
        formData.post(route("equipment.store"), {
            onFinish: () => equipmentForm.value?.endSubmit(),
        });
    }
};
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
