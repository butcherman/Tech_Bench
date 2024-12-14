<template>
    <VueForm
        ref="form"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="submitRoute"
        submit-method="put"
        submit-text="Run Report"
    >
        <div class="text-center mb-4">
            <CheckboxSwitch
                id="all-users"
                name="allUsers"
                label="All Users?"
                inline
                @change="showUserList = !showUserList"
            />
            <CheckboxSwitch
                id="disabled-users"
                name="disabledUsers"
                label="Include Disabled Users?"
                inline
            />
        </div>
        <SelectBoxInput
            v-if="showUserList"
            id="user-list"
            name="user_list"
            label="Select Users"
            text-field="full_name"
            value-field="username"
            :list="userList"
            :size="10"
        />
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import CheckboxSwitch from "@/Forms/_Base/CheckboxSwitch.vue";
import SelectBoxInput from "@/Forms/_Base/SelectBoxInput.vue";
import { ref } from "vue";
import { array, boolean, object } from "yup";

defineProps<{
    userList: user[];
    submitRoute: string;
}>();

const showUserList = ref(true);

const initValues = {
    allUsers: !showUserList.value,
    disabledUsers: false,
    user_list: [],
};
const schema = object({
    allUsers: boolean().required(),
    disabledUsers: boolean().required(),
    user_list: array()
        .when("allUsers", {
            is: false,
            then: (schema) => schema.min(1),
            otherwise: (schema) => schema.nullable(),
        })
        .label("Users"),
});
</script>
