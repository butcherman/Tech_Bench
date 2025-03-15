<script setup lang="ts">
import SwitchInput from "../_Base/SwitchInput.vue";
import VueForm from "@/Forms/_Base/VueForm.vue";
import { object, boolean } from "yup";

const props = defineProps<{
    settings: userSettings[];
    user: user;
}>();

/*
|-------------------------------------------------------------------------------
| Validation
|-------------------------------------------------------------------------------
*/

/**
 * Dynamically build the Initial Values based on available settings
 */
const initValues = () => {
    let init: { [key: string]: boolean } = {};
    props.settings.forEach((setting) => {
        init[`type_id_${setting.setting_type_id}`] = setting.value;
    });

    return {
        settingList: init,
    };
};

/**
 * Dynamically build the Schema
 */
const schema = () => {
    let schema: { [key: string]: any } = {};
    props.settings.forEach((setting) => {
        schema[`type_id_${setting.setting_type_id}`] = boolean().required();
    });

    return {
        settingList: object(schema),
    };
};
</script>

<template>
    <VueForm
        :initial-values="initValues()"
        :validation-schema="schema()"
        :submit-route="$route('user.user-settings.update', user.username)"
        submit-method="put"
        submit-text="Update Settings"
    >
        <template v-for="(setting, key) in settings" :key="key">
            <SwitchInput
                :id="`notification-${key}`"
                :name="`settingList.type_id_${setting.setting_type_id}`"
                :label="setting.name"
            />
        </template>
    </VueForm>
</template>
