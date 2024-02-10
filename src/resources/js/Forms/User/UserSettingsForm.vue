<template>
    <VueForm
        ref="form"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="$route('user.user-settings.update', user.username)"
        submit-method="put"
    >
        <template v-for="(item, key) in settings">
            <CheckboxSwitch
                :id="`notification-${key}`"
                :name="`settingList.type_id_${item.setting_type_id}`"
                :label="item.name"
            />
        </template>
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import CheckboxSwitch from "../_Base/CheckboxSwitch.vue";
import { ref } from "vue";
import { boolean, object, string } from "yup";

const props = defineProps<{
    settings: userSettings[];
    user: user;
}>();

const getInitValues = () => {
    let init: { [key: string]: boolean } = {};
    props.settings.forEach((setting) => {
        init[`type_id_${setting.setting_type_id}`] = setting.value;
    });

    return {
        settingList: init,
    };
};

// TODO - Finish This
const getSchema = () => {
    console.log("schema");

    let schema: { [key: string]: any } = {};

    props.settings.forEach((setting) => {
        schema[`type_id_${setting.setting_type_id}`] = boolean().required();
    });

    return {
        settingList: object(schema),
    };
};

const initValues = getInitValues();
const schema = object({});
</script>
