<script setup lang="ts">
import SwitchInput from "@/core/forms/components/validatedInputs/SwitchInput.vue";
import VueForm from "@/core/forms/components/VueForm.vue";
import { object, boolean } from "yup";
import { update } from "@/wayfinder/routes/user/user-settings";

defineEmits<{
    success: [];
}>();

const props = defineProps<{
    settings: UserSettings[];
    user: User;
}>();

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
        name="user-preferences-form"
        submit-method="put"
        submit-text="Update Preferences"
        :initial-values="initValues()"
        :validation-schema="schema()"
        :submit-route="update.url(user.username)"
        do-not-reset
        @success="$emit('success')"
    >
        <template v-for="setting in settings" :key="setting.setting_type_id">
            <div class="border border-slate-300 p-2 rounded-lg">
                <h5>{{ setting.name }}</h5>
                <p>
                    {{ setting.description }}
                </p>
                <div class="flex gap-2 flex-row-reverse">
                    <div>
                        <SwitchInput
                            :name="`settingList.type_id_${setting.setting_type_id}`"
                        />
                    </div>
                </div>
            </div>
        </template>
    </VueForm>
</template>
