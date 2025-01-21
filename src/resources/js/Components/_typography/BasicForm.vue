<template>
    <Card title="Basic Text Form">
        <VueForm
            :initial-values="initValues"
            :validation-schema="schema"
            submit-route="#"
            submit-method="post"
            submit-text="Basic Text Form"
        >
            <TextInput
                id="text-input"
                label="Text Input"
                name="text_input"
                placeholder="This is a Text Input"
                help="This is Help Text"
            />
            <fieldset class="border p-2 text-muted">
                <legend>Text Input Array</legend>
                <TextInputArray
                    id="text-input-array"
                    name="text_field_array"
                    draggable
                />
            </fieldset>
            <PasswordInput
                id="password-input"
                label="Password Input"
                name="password_input"
            />
            <PhoneInput
                id="phone-input"
                label="Phone Number Input"
                name="phone_input"
            />
            <fieldset class="border p-2 text-muted">
                <legend>Phone Input Array</legend>
                <PhoneInputArray
                    id="phone-input-array"
                    name="phone_input_array"
                    :phone-types="[
                        {
                            text: 'Mobile',
                            value: 'mobile',
                        },
                        {
                            text: 'Work',
                            value: 'work',
                        },
                        {
                            text: 'Home',
                            value: 'home',
                        },
                    ]"
                />
            </fieldset>
            <AutoCompleteInput
                id="auto-complete-input"
                label="Auto Complete Input"
                name="auto_complete_input"
                :data-list="['random', 'list', 'of', 'strings']"
            />
            <SelectInput
                id="select-input"
                label="Select Input"
                name="select_input"
                :list="normalSelect"
            />
            <TextAreaInput
                id="text-area-input"
                label="Text Area Input"
                name="text_area_input"
            />
        </VueForm>
    </Card>
</template>

<script setup lang="ts">
import AutoCompleteInput from "@/Forms/_Base/AutoCompleteInput.vue";
import Card from "../_Base/Card.vue";
import PasswordInput from "@/Forms/_Base/PasswordInput.vue";
import PhoneInput from "@/Forms/_Base/PhoneInput.vue";
import PhoneInputArray from "@/Forms/_Base/PhoneInputArray.vue";
import SelectInput from "@/Forms/_Base/SelectInput.vue";
import TextAreaInput from "@/Forms/_Base/TextAreaInput.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import TextInputArray from "@/Forms/_Base/TextInputArray.vue";
import VueForm from "@/Forms/_Base/VueForm.vue";
import { object } from "yup";
import { ref } from "vue";

const normalSelect = ref([
    { text: "New York", value: "NY" },
    { text: "Rome", value: "RM" },
    { text: "London", value: "LDN" },
    { text: "Istanbul", value: "IST" },
    { text: "Paris", value: "PRS" },
]);

const initValues = {
    text_field_array: ["", ""],
    phone_input_array: [{ type: "mobile", number: "", ext: "" }],
};
const schema = object({});
</script>
