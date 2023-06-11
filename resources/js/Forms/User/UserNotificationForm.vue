<template>
    <VueForm
        ref="userNotificationForm"
        :initial-values="initValues"
        :validation-schema="schema"
        submit-text="Update Notification Settings"
        @submit="onSubmit"
    >
        <template v-for="(item, key) in settings">
            <CheckboxSwitch
                :id="`notification-${key}`"
                :name="`type_id_${item.setting_type_id}`"
                :label="item.name"
            />
        </template>
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "../_Base/VueForm.vue";
import CheckboxSwitch from "../_Base/CheckboxSwitch.vue";
import { ref, onMounted } from "vue";
import { useForm } from "@inertiajs/vue3";

onMounted(() => assignValues());

const props = defineProps<{
    username: string;
    settings: userSettings[];
}>();

const userNotificationForm = ref<InstanceType<typeof VueForm> | null>(null);
const initValues = {};
const schema = {};

const assignValues = () => {
    props.settings.forEach((setting) => {
        userNotificationForm.value?.setFieldValue(
            `type_id_${setting.setting_type_id}`,
            setting.value
        );
    });
};

const onSubmit = (form: { [key: string]: boolean | undefined }) => {
    const formData = useForm({ settingsData: form });
    formData.post(route("user.settings.notifications", props.username), {
        onFinish: () => userNotificationForm.value?.endSubmit(),
    });
};
</script>
