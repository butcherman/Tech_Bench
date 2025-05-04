<script setup lang="ts">
import Editor from "../_Base/Editor.vue";
import PickListInput from "../_Base/PickListInput.vue";
import RadioGroupInput from "../_Base/RadioGroupInput.vue";
import SelectInput from "../_Base/SelectInput.vue";
import SwitchInput from "../_Base/SwitchInput.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import VueForm from "@/Forms/_Base/VueForm.vue";
import { computed, ref } from "vue";
import { object, string, boolean, array } from "yup";
import { shrinkHide, growShow } from "@/Composables/animations.module";
import {
    equipmentList,
    siteList,
} from "../../Composables/Customer/CustomerData.module";

type noteType = "general" | "site" | "equipment";

const props = defineProps<{
    customer: customer;
    currentSite: customerSite | null;
    siteList: customerSite[];
    equipment?: customerEquipment;
    note?: customerNote;
}>();

/*
|-------------------------------------------------------------------------------
| Determine the type of note being created/edited.
|-------------------------------------------------------------------------------
*/
const getInitNoteType = (): noteType => {
    if ((props.note && props.note.cust_equip_id) || props.equipment) {
        return "equipment";
    }

    if (
        (props.note && props.note.sites.length > 0) ||
        (props.currentSite && props.siteList.length > 1)
    ) {
        return "site";
    }

    return "general";
};

const updateNoteType = (type: noteType): void => {
    noteType.value = type;
    console.log(type);
};

const noteType = ref<noteType>(getInitNoteType());

const noteTypes: { label: string; value: noteType }[] = [
    {
        label: "General Note",
        value: "general",
    },
    {
        label: "Equipment Note",
        value: "equipment",
    },
];

if (props.siteList.length > 1) {
    noteTypes.push({
        label: "Site Note",
        value: "site",
    });
}

/*
|-------------------------------------------------------------------------------
| Handle Form
|-------------------------------------------------------------------------------
*/
const submitRoute = computed(() => {
    return props.note
        ? route("customers.notes.update", [
              props.customer.slug,
              props.note.note_id,
          ])
        : route("customers.notes.store", props.customer.slug);
});
const submitMethod = computed(() => (props.note ? "put" : "post"));
const submitText = computed(() => (props.note ? "Edit Note" : "Create Note"));

/*
|-------------------------------------------------------------------------------
| Vee Validate
|-------------------------------------------------------------------------------
*/
const getInitSiteList = () => {
    if (props.note && props.note.sites) {
        return props.note.sites.map((site) => site.cust_site_id);
    }

    if (props.currentSite) {
        return [props.currentSite.cust_site_id];
    }

    return [];
};

const initValues = {
    subject: props.note?.subject,
    note_type: noteType.value,
    urgent: props.note?.urgent || false,
    site_list: getInitSiteList(),
    cust_equip_id: props.note?.cust_equip_id || props.equipment?.cust_equip_id,
    details: props.note?.details,
};
const schema = object({
    subject: string().required(),
    note_type: string().required().label("Note Type"),
    urgent: boolean().required(),
    site_list: array().when("note_type", {
        is: "site",
        then: (schema) =>
            schema
                .min(1, "Select at least one site")
                .required("Select at least one site"),
        otherwise: (schema) => schema.nullable(),
    }),
    cust_equip_id: string().when("note_type", {
        is: "equipment",
        then: (schema) =>
            schema.required("Select the Equipment to attach this note to"),
        otherwise: (schema) => schema.nullable(),
    }),
    details: string().required("Note Details are required"),
});
</script>

<template>
    <VueForm
        :initial-values="initValues"
        :submit-method="submitMethod"
        :submit-route="submitRoute"
        :submit-text="submitText"
        :validation-schema="schema"
    >
        <TextInput id="subject" name="subject" label="Subject" focus />
        <div class="flex justify-center">
            <SwitchInput id="urgent" name="urgent" label="Mark Note Urgent" />
        </div>
        <div class="flex justify-center mt-2">
            <RadioGroupInput
                id="note-type"
                name="note_type"
                :list="noteTypes"
                class="text-center"
                inline
                @change="updateNoteType"
            />
        </div>
        <TransitionGroup @enter="growShow" @leave="shrinkHide">
            <PickListInput
                v-if="noteType === 'site'"
                id="site-list"
                name="site_list"
                label="Select which Sites this note is relevant to"
                label-field="site_name"
                value-field="cust_site_id"
                :list="siteList"
            />
            <SelectInput
                v-if="noteType === 'equipment'"
                id="cust_equip-id"
                name="cust_equip_id"
                label="Select which Equipment this note is relevant to"
                :list="equipmentList"
                text-field="equip_name"
                value-field="cust_equip_id"
            >
                <template #option="{ option }">
                    {{ option.equip_name }}
                    <span v-if="!currentSite">
                        &nbsp; ({{ option.sites[0].site_name }})
                    </span>
                </template>
            </SelectInput>
        </TransitionGroup>
        <Editor
            id="note"
            name="details"
            label="Note Details"
            image-folder="customer_notes"
        />
    </VueForm>
</template>
