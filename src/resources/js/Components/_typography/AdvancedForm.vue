<template>
    <Card title="Advanced Form">
        <VueForm
            :initial-values="initValues"
            :validation-schema="schema"
            submit-route="#"
            submit-method="post"
            submit-text="Advanced Form"
        >
            <SelectInput
                id="select-input-group"
                label="Grouped Select Input"
                name="grouped_select_input"
                text-field="label"
                :list="selectGroup"
                group-text-field="label"
                group-children-field="items"
            />
            <MultiSelectInput
                id="multiselect-input"
                label="Multi Select Input"
                name="multi_select"
                :list="normalSelect"
            />
            <SelectBoxInput
                id="select-box-input"
                label="Select Box Input"
                name="select_box_input"
                :list="normalSelect"
                help="Standard Select Box Input"
            />
            <OtpInput
                id="otp-input"
                label="OTP Input"
                name="otp_input"
                :length="6"
            />
            <SwitchInput
                id="switch-input"
                label="Switch Input"
                name="switch_input"
                center
            />
            <fieldset class="flex justify-center my-2 border p-2 text-muted">
                <legend>Radio Button Group</legend>
                <RadioGroupInput
                    id="radio-group-input"
                    name="radio_group_input"
                    :list="['Cheese', 'Mushrooms', 'Pepperoni']"
                    help="This is some Help Text"
                    inline
                />
            </fieldset>
            <RangeInput
                id="range-input"
                name="range_input"
                label="Range Input"
                :min="20"
                :max="200"
                help="This is a Range Input"
            />
        </VueForm>
    </Card>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import SelectInput from "@/Forms/_Base/SelectInput.vue";
import MultiSelectInput from "@/Forms/_Base/MultiSelectInput.vue";
import OtpInput from "@/Forms/_Base/OtpInput.vue";
import SwitchInput from "@/Forms/_Base/SwitchInput.vue";
import Card from "../_Base/Card.vue";
import { object } from "yup";
import { ref } from "vue";
import RadioGroupInput from "@/Forms/_Base/RadioGroupInput.vue";
import RangeInput from "@/Forms/_Base/RangeInput.vue";
import SelectBoxInput from "@/Forms/_Base/SelectBoxInput.vue";

const normalSelect = ref([
    { label: "New York", value: "NY" },
    { label: "Rome", value: "RM" },
    { label: "London", value: "LDN" },
    { label: "Istanbul", value: "IST" },
    { label: "Paris", value: "PRS" },
]);

const selectGroup = ref([
    {
        label: "Germany",
        items: [
            { label: "Berlin", value: "Berlin" },
            { label: "Frankfurt", value: "Frankfurt" },
            { label: "Hamburg", value: "Hamburg" },
            { label: "Munich", value: "Munich" },
        ],
    },
    {
        label: "USA",
        items: [
            { label: "Chicago", value: "Chicago" },
            { label: "Los Angeles", value: "Los Angeles" },
            { label: "New York", value: "New York" },
            { label: "San Francisco", value: "San Francisco" },
        ],
    },
    {
        label: "Japan",
        items: [
            { label: "Kyoto", value: "Kyoto" },
            { label: "Osaka", value: "Osaka" },
            { label: "Tokyo", value: "Tokyo" },
            { label: "Yokohama", value: "Yokohama" },
        ],
    },
]);

const initValues = {
    range_input: 175,
    select_box_input: ["RM", "IST"],
};
const schema = object({});
</script>
