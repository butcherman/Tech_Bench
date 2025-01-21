<template>
    <VueForm
        ref="form"
        :initial-values="initValues"
        :submit-route="submitRoute"
        :submit-method="submitMethod"
        :submit-text="submitText"
        :validation-schema="schema"
    >
        <TextInput
            id="description"
            name="description"
            label="Phone Type Description"
            focus
        />
        <label class="form-label">Icon:</label>
        <RadioGroupInput
            id="icon-class"
            name="icon_class"
            :list="iconList"
            inline
            class="mb-4"
        >
            <template #item-label="{ item }">
                <fa-icon
                    :icon="typeof item === 'string' ? 'item' : item.value"
                    class="text-muted mx-2"
                />
            </template>
        </RadioGroupInput>
    </VueForm>
</template>

<script setup lang="ts">
import RadioGroupInput from "../_Base/RadioGroupInput.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import VueForm from "@/Forms/_Base/VueForm.vue";
import { computed } from "vue";
import { object, string } from "yup";

type adminPhoneType = {
    phone_type_id: number;
} & phoneType;

const props = defineProps<{
    phoneType?: adminPhoneType;
}>();

/*
|-------------------------------------------------------------------------------
| Handle Form
|-------------------------------------------------------------------------------
*/
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
|-------------------------------------------------------------------------------
| Validation
|-------------------------------------------------------------------------------
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
|-------------------------------------------------------------------------------
| List of possible icon values
|-------------------------------------------------------------------------------
*/
const iconList = [
    {
        label: "home",
        value: "fa-home",
    },
    {
        label: "briefcase",
        value: "fa-briefcase",
    },
    {
        label: "phone",
        value: "fa-phone",
    },
    {
        label: "phone-volume",
        value: "fa-phone-volume",
    },
    {
        label: "phone-slash",
        value: "fa-phone-slash",
    },
    {
        label: "square-phone",
        value: "fa-square-phone",
    },
    {
        label: "phone-flip",
        value: "fa-phone-flip",
    },
    {
        label: "pager",
        value: "fa-pager",
    },
    {
        label: "mobile",
        value: "fa-mobile-alt",
    },
    {
        label: "voicemail",
        value: "fa-voicemail",
    },
];
</script>
