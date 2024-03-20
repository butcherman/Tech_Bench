<template>
    <VueForm
        ref="customerNoteForm"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="submitRoute"
        :submit-method="submitMethod"
        :submit-text="submitText"
    >
        <TextInput id="subject" name="subject" label="Subject" focus />
        <div class="text-center mt-4">
            <CheckboxSwitch
                id="urgent"
                name="urgent"
                label="Mark Note As Urgent"
                inline
            />
        </div>
        <RadioGroupInput
            id="note-type"
            name="note_type"
            :list="formTypes"
            class="text-center"
            inline
            @change="updateNoteType"
        />
        <SelectBoxInput
            v-if="noteType === 'site'"
            id="equipment-site-list"
            name="site_list"
            label="Select which Sites this note is relevant to"
            text-field="site_name"
            value-field="cust_site_id"
            :list="siteList"
        />
        <SelectInput
            v-if="noteType === 'equipment'"
            id="cust_equip-id"
            name="cust_equip_id"
            label="Select which Equipment this note is relevant to"
            :list="equipList"
            text-field="equip_name"
            value-field="cust_equip_id"
        />
        <Editor id="note" name="details" label="Note Details" />
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import RadioGroupInput from "../_Base/RadioGroupInput.vue";
import CheckboxSwitch from "../_Base/CheckboxSwitch.vue";
import SelectBoxInput from "../_Base/SelectBoxInput.vue";
import SelectInput from "../_Base/SelectInput.vue";
import Editor from "../_Base/Editor.vue";
import { computed, ref } from "vue";
import { object, string, boolean, array } from "yup";

const props = defineProps<{
    customer: customer;
    siteList: customerSite[];
    equipList: any[];
    currentSite: customerSite | null;
    note?: customerNote;
}>();

const customerNoteForm = ref<InstanceType<typeof VueForm> | null>(null);

/**
 * Determine the note type based on prop parameters
 */
const getNoteType = (): "general" | "site" | "equipment" => {
    if (props.note && props.note.cust_equip_id) {
        return "equipment";
    }

    if (
        (props.note && props.note.customer_site.length > 0) ||
        (props.currentSite && props.siteList.length > 1)
    ) {
        return "site";
    }

    return "general";
};
const noteType = ref(getNoteType());

const submitRoute = computed(() => {
    if (props.currentSite) {
        return props.note
            ? route("customers.notes.update", [
                  props.customer.slug,
                  props.note.note_id,
              ])
            : route("customers.site-note.store", [
                  props.customer.slug,
                  props.currentSite.site_slug,
              ]);
    }

    return props.note
        ? route("customers.notes.update", [
              props.customer.slug,
              props.note.note_id,
          ])
        : route("customers.notes.store", props.customer.slug);
});
const submitMethod = computed(() => (props.note ? "put" : "post"));
const submitText = computed(() => (props.note ? "Edit Note" : "Create Note"));

const initValues = {
    subject: props.note?.subject,
    note_type: noteType.value,
    urgent: props.note?.urgent || false,
    site_list:
        props.note?.customer_site.map((site) => site.cust_site_id) || [
            props.currentSite?.cust_site_id,
        ] ||
        [],
    cust_equip_id: props.note?.cust_equip_id || null,
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

/**
 * Notes can be assigned to a specific equipment or site.  Unselected will be
 * a General note
 */
const formTypes = [
    {
        text: "General Note",
        value: "general",
    },
    {
        text: "Equipment Note",
        value: "equipment",
    },
];
if (props.siteList.length > 1) {
    formTypes.push({
        text: "Site Note",
        value: "site",
    });
}

const updateNoteType = (type: "general" | "site" | "equipment") => {
    noteType.value = type;
};
</script>
