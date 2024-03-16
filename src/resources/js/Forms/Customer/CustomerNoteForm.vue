<template>
    <VueForm
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="submitRoute"
        :submit-method="submitMethod"
        :submit-text="submitText"
        testing
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
            :disabled="note ? true : false"
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
import { object, string } from "yup";

const props = defineProps<{
    customer: customer;
    siteList: customerSite[];
    equipList: any[];
    note?: customerNote;
}>();

const noteType = ref("general");

const submitRoute = computed(() =>
    props.note
        ? route("customers.notes.update", [
              props.customer.slug,
              props.note.note_id,
          ])
        : route("customers.notes.store", props.customer.slug)
);
const submitMethod = computed(() => (props.note ? "put" : "post"));
const submitText = computed(() => (props.note ? "Edit Note" : "Create Note"));

const initValues = {};
const schema = object({});

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
