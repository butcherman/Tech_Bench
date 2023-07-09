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
            >
                <template #start-group-text>
                    <span class="input-group-text pointer" title="Drag to Re-Order" v-tooltip>
                        <fa-icon icon="sort" class="text-primary" />
                    </span>
                </template>
            </TextFieldArray>
        </fieldset>
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import SelectInput from "../_Base/SelectInput.vue";
import TextFieldArray from "../_Base/TextFieldArray.vue";
import { useForm } from "@inertiajs/vue3";
import { ref, reactive, onMounted } from "vue";
import { object, string } from "yup";

defineProps<{
    categories: categoryList[];
}>();

const equipmentForm = ref<InstanceType<typeof VueForm> | null>(null);
const initValues = {
    custData: ["", "", ""],
};
const schema = object({});

type equipForm = {
    cat_id: number;
    name: string;
    custData: string[];
}

const onSubmit = (form: equipForm) => {
    // console.log(form);
    console.log('submitted', form);

    // const formData = useForm(form);
    // console.log(formData);
    //

    equipmentForm.value?.endSubmit();
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
