<template>
    <VueForm
        ref="form"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="submitRoute"
        submit-method="put"
        submit-text="Run Report"
    >
        <div class="row">
            <div class="col">
                <TextInput
                    id="start-date"
                    type="date"
                    name="start_date"
                    label="Start Date"
                    focus
                />
            </div>
            <div class="col">
                <TextInput
                    id="end-date"
                    type="date"
                    name="end_date"
                    label="End Date"
                    focus
                />
            </div>
            <SelectBoxInput
                id="user-list"
                name="user_list"
                label="Select Users"
                text-field="full_name"
                value-field="username"
                :list="userList"
                :size="10"
            />
        </div>
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import SelectBoxInput from "@/Forms/_Base/SelectBoxInput.vue";
import { array, object, date, ref } from "yup";

defineProps<{
    userList: user[];
    submitRoute: string;
}>();

const today = new Date();
const initValues = {
    start_date: new Date(new Date().setDate(today.getDate() - 30))
        .toJSON()
        .slice(0, 10),
    end_date: today.toJSON().slice(0, 10),
    user_list: [],
};
const schema = object({
    start_date: date().required().label("Start Date"),
    end_date: date()
        .required()
        .min(ref("start_date"), "Start Date must be after End Date")
        .label("End Date"),
    user_list: array().min(1).label("Users"),
});
</script>
