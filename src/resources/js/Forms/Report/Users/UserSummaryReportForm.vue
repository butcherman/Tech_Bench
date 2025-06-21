<script setup lang="ts">
import PickListInput from "@/Forms/_Base/PickListInput.vue";
import SwitchInput from "@/Forms/_Base/SwitchInput.vue";
import VueForm from "@/Forms/_Base/VueForm.vue";
import { array, boolean, object } from "yup";
import { ref } from "vue";

defineProps<{
    userList: user[];
}>();

const allSelected = ref<boolean>(true);

/*
|-------------------------------------------------------------------------------
| Vee Validate
|-------------------------------------------------------------------------------
*/
const initValues = {
    all_users: true,
    disabled_users: false,
    user_list: [],
};
const schema = object({
    all_users: boolean().required(),
    disabled_users: boolean().required(),
    user_list: array().when("all_users", {
        is: false,
        then: (schema) => schema.min(1, "You must select at least one user"),
        otherwise: (schema) => schema.nullable(),
    }),
});
</script>

<template>
    <VueForm
        submit-method="put"
        submit-text="Run Report"
        :initial-values="initValues"
        :submit-route="$route('reports.run', ['users', 'user-summary-report'])"
        :validation-schema="schema"
    >
        <div class="flex justify-center">
            <div>
                <SwitchInput
                    id="all-users"
                    name="all_users"
                    label="All Users"
                    @change="allSelected = !allSelected"
                />
                <div v-show="allSelected">
                    <SwitchInput
                        id="disabled-users"
                        name="disabled_users"
                        label="Include Disabled Users"
                    />
                </div>
            </div>
        </div>
        <div v-show="!allSelected" class="flex justify-center">
            <PickListInput
                id="user-list"
                name="user_list"
                :list="userList"
                label-field="full_name"
                value-field="username"
            />
        </div>
    </VueForm>
</template>
