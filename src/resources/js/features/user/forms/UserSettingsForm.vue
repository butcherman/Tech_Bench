<script setup lang="ts">
import SwitchInput from "@/core/forms/SwitchInput.vue";
import VueForm from "@/core/forms/VueForm.vue";
import { object, boolean } from "yup";
import { update } from "@/wayfinder/routes/user/user-settings";

const props = defineProps<{
    settings: UserSettings[];
    user: User;
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
        name="user-settings-form"
        :initial-values="initValues()"
        :validation-schema="schema()"
        :submit-route="update.url(user.username)"
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
