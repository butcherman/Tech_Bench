<script setup lang="ts">
import DatePicker from "@/Forms/_Base/DatePicker.vue";
import PickListInput from "@/Forms/_Base/PickListInput.vue";
import VueForm from "@/Forms/_Base/VueForm.vue";
import { object, date, array, ref } from "yup";

defineProps<{
    userList: user[];
}>();

const today = new Date();

/*
|-------------------------------------------------------------------------------
| Vee Validate
|-------------------------------------------------------------------------------
*/
const initValues = {
    start_date: new Date(new Date().setDate(today.getDate() - 30)),
    end_date: today,
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

<template>
    <VueForm
        submit-method="put"
        submit-text="Run Report"
        :initial-values="initValues"
        :submit-route="
            $route('reports.run', ['users', 'user-login-activity-report'])
        "
        :validation-schema="schema"
    >
        <div class="grid grid-cols-2 gap-3">
            <DatePicker
                id="start-date"
                name="start_date"
                label="Start Date"
                focus
            />
            <DatePicker id="start-date" name="end_date" label="End Date" />
        </div>
        <PickListInput
            id="user-list"
            name="user_list"
            :list="userList"
            label-field="full_name"
            value-field="username"
        />
    </VueForm>
</template>
