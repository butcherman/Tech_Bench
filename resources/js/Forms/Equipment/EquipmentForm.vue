<template>
    <VueForm
        ref="equipmentForm"
        :initial-values="initValues"
        :validation-schema="schema"
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
import { ref } from "vue";
import { object, string, number, array } from "yup";

defineProps<{
    categories: categoryList[];
    dataList: string[];
}>();

const equipmentForm = ref<InstanceType<typeof VueForm> | null>(null);
const initValues = {
    cat_id: null,
    name: '',
    custData: [null, null, null],
};
const schema = object({
    cat_id: number().required().label('Category'),
    name: string().required(),
    custData: array().ensure().min(1, 'You must have at least one entry'),
});

type equipForm = {
    cat_id: number;
    name: string;
    custData: string[];
}

const onSubmit = (form: equipForm) => {
    console.log(form);
    const formData = useForm(form);

    formData.post(route('equipment.store'), {
        onFinish: () => equipmentForm.value?.endSubmit(),
    });
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
