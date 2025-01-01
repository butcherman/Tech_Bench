<template>
    <VueForm
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="submitRoute"
        :submit-method="submitMethod"
        :submit-text="submitText"
    >
        <TextInput
            id="description"
            name="description"
            label="Phone Type Description"
            focus
        />
        <RadioInput
            id="icon-class"
            name="icon_class"
            label="Display Icon"
            help="Select an Icon to go with this type"
            :list="iconList"
            inline
        >
            <template #label="{ item }">
                <v-icon :icon="item.value" class="text-muted w-100" />
            </template>
        </RadioInput>
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import { object, string } from "yup";
import { computed } from "vue";
import RadioInput from "../_Base/RadioInput.vue";

const props = defineProps<{
    phoneType?: phoneType;
}>();

const submitRoute = computed(() =>
    props.phoneType
        ? route("admin.phone-types.update", props.phoneType.phone_type_id)
        : route("admin.phone-types.store")
);
const submitMethod = computed(() => (props.phoneType ? "put" : "post"));
const submitText = computed(() =>
    props.phoneType ? "Update Phone Type" : "Create Phone Type"
);

/*
|---------------------------------------------------------------------------
| Validation
|---------------------------------------------------------------------------
*/
const initValues = {
    description: props.phoneType?.description,
    icon_class: props.phoneType?.icon_class,
};
const schema = object({
    description: string().required(),
    icon_class: string().required("Please chose an icon"),
});

/*
|---------------------------------------------------------------------------
| All Possible Icons that can be used for phone number types
|---------------------------------------------------------------------------
*/
const iconList = [
    {
        text: "home",
        value: "fa-home",
    },
    {
        text: "briefcase",
        value: "fa-briefcase",
    },
    {
        text: "phone",
        value: "fa-phone",
    },
    {
        text: "phone-volume",
        value: "fa-phone-volume",
    },
    {
        text: "phone-slash",
        value: "fa-phone-slash",
    },
    {
        text: "square-phone",
        value: "fa-square-phone",
    },
    {
        text: "phone-flip",
        value: "fa-phone-flip",
    },
    {
        text: "pager",
        value: "fa-pager",
    },
    {
        text: "mobile",
        value: "fa-mobile",
    },
    {
        text: "voicemail",
        value: "fa-voicemail",
    },
];
</script>
