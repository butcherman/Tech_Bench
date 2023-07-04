<template>
    <VueForm
        ref="userNotificationForm"
        :initial-values="initValues"
        :validation-schema="schema"
        submit-text="Update Notification Settings"
        @submit="onSubmit"
    >
        <CheckboxSwitch
            v-if="twoFa.allow_sms"
            id="sms-notification"
            name="sms_notification"
            label="Receive Two-Factor Auth code via SMS"
        />
        <Collapse :visible="showTel">
            <div class="row justify-content-center">
                <div class="col-10 border">
                    <PhoneInput
                        id="phone-number"
                        name="phone"
                        label="Mobile Phone Number"
                    />
                </div>
            </div>
        </Collapse>
        <template v-for="(item, key) in notifications">
            <CheckboxSwitch
                :id="`notification-${key}`"
                :name="`settingList.type_id_${item.setting_type_id}`"
                :label="item.name"
            />
        </template>
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "../_Base/VueForm.vue";
import CheckboxSwitch from "../_Base/CheckboxSwitch.vue";
import PhoneInput from "@/Forms/_Base/PhoneInput.vue";
import Collapse from "@/Components/_Base/Collapse.vue";
import { ref, computed, onMounted } from "vue";
import { useForm } from "@inertiajs/vue3";
import { object, string, boolean } from "yup";

onMounted(() => assignValues());

const props = defineProps<{
    username: string;
    notifications: userSettings[];
    twoFa: {
        allow_sms: boolean;
        sms_notifications: boolean;
        phone: string;
    };
}>();

const userNotificationForm = ref<InstanceType<typeof VueForm> | null>(null);
const initValues = {
    sms_notification: props.twoFa.sms_notifications,
    phone: props.twoFa.phone,
};
const schema = object({
    sms_notification: boolean().required(),
    phone: string().when("sms_notification", {
        is: true,
        then: (schema) =>
            schema.required(
                "You must enter your mobile number to get SMS messages"
            ),
        otherwise: (schema) => schema.nullable(),
    }),
});
const showTel = computed(() =>
    userNotificationForm.value
        ? userNotificationForm.value.getFieldValue("sms_notification")
        : false
);

const assignValues = () => {
    props.notifications.forEach((setting) => {
        userNotificationForm.value?.setFieldValue(
            `settingList.type_id_${setting.setting_type_id}`,
            setting.value
        );
    });
};

const onSubmit = (form: { [key: string]: boolean | undefined }) => {
    const formData = useForm(form);
    formData.post(route("user.settings.notifications", props.username), {
        onFinish: () => userNotificationForm.value?.endSubmit(),
    });
};
</script>
