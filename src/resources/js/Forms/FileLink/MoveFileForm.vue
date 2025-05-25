<script setup lang="ts">
import PickListInput from "../_Base/PickListInput.vue";
import RadioGroupInput from "../_Base/RadioGroupInput.vue";
import SelectInput from "../_Base/SelectInput.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import VueForm from "@/Forms/_Base/VueForm.vue";
import { array, object, string } from "yup";
import { ref } from "vue";
import { growShow, shrinkHide } from "@/Composables/animations.module";

type fileCategory = "general" | "site" | "equipment";

defineEmits<{
    success: [];
}>();

const props = defineProps<{
    link: fileLink;
    customer: customer;
    file: fileLinkFile;
    equipmentList: customerEquipment[];
    fileTypes: customerFileType[];
}>();

const fileCategory = ref<fileCategory>("general");
const updateFileCategory = (type: fileCategory): void => {
    fileCategory.value = type;
};

const fileCategories: { label: string; value: fileCategory }[] = [
    {
        label: "General File",
        value: "general",
    },
    {
        label: "Equipment File",
        value: "equipment",
    },
];

if (props.customer.site_count > 1) {
    fileCategories.push({
        label: "Site File",
        value: "site",
    });
}

/*
|-------------------------------------------------------------------------------
| Vee Validate
|-------------------------------------------------------------------------------
*/
const initValues = {
    cust_id: props.customer.cust_id,
    name: props.file.file_name,
    file_type: fileCategory.value,
    site_list: [],
    cust_equip_id: null,
    file_type_id: null,
};
const schema = object({
    cust_id: string().required(),
    name: string().required(),
    file_type: string().required(),
    site_list: array().when("file_type", {
        is: "site",
        then: (schema) =>
            schema
                .min(1, "Select at least one site")
                .required("Select at least one site"),
        otherwise: (schema) => schema.nullable(),
    }),
    cust_equip_id: string().when("file_type", {
        is: "equipment",
        then: (schema) =>
            schema.required("Select the Equipment to attach this file to"),
        otherwise: (schema) => schema.nullable(),
    }),
    file_type_id: string().required("What type of file is this?"),
});
</script>

<template>
    <VueForm
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="
            $route('links.files.update', [link.link_id, file.file_id])
        "
        submit-method="put"
        submit-text="Move File"
        @success="$emit('success')"
    >
        <TextInput
            id="name"
            name="name"
            label="Name"
            placeholder="Enter A Descriptive Name"
        />
        <SelectInput
            id="type"
            name="file_type_id"
            label="File Type"
            :list="fileTypes"
            text-field="description"
            value-field="file_type_id"
        />
        <div class="flex justify-center mt-2">
            <RadioGroupInput
                id="file-category"
                name="file_type"
                :list="fileCategories"
                inline
                @change="updateFileCategory"
            />
        </div>
        <TransitionGroup @enter="growShow" @leave="shrinkHide">
            <PickListInput
                v-if="fileCategory === 'site'"
                id="site-list"
                name="site_list"
                label="Select which Sites this file is relevant to"
                label-field="site_name"
                value-field="cust_site_id"
                :list="customer.sites"
            />
            <SelectInput
                v-if="fileCategory === 'equipment'"
                id="cust_equip-id"
                name="cust_equip_id"
                label="Select which Equipment this file is relevant to"
                :list="equipmentList"
                text-field="equip_name"
                value-field="cust_equip_id"
            >
                <template #option="{ option }">
                    {{ option.equip_name }}
                </template>
            </SelectInput>
        </TransitionGroup>
    </VueForm>
</template>
