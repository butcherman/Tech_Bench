<template>
    <VueForm
        ref="notificationForm"
        :initial-values="initValues"
        :validation-schema="schema"
        submit-text="Send Notification"
        @submit="onSubmit"
    >
        <p class="text-center">
            A notification will be delivered to the users notifications as well
            as via email.
        </p>
        <TextInput id="subject" name="subject" label="Subject" />
        <TextAreaInput id="message" name="message" label="Message" :rows="6" />
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import TextAreaInput from "@/Forms/_Base/TextAreaInput.vue";
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import { object, string } from "yup";

const emit = defineEmits(["completed"]);
const props = defineProps<{
    user: user;
}>();

const notificationForm = ref<InstanceType<typeof VueForm> | null>(null);
const initValues = {
    subject: "",
    message: "",
};
const schema = object({
    subject: string().required(),
    message: string().required(),
});

type notificationForm = {
    subject: string;
    message: string;
};

const onSubmit = (form: notificationForm) => {
    const formData = useForm(form);

    formData.post(route("admin.users.send-notification", props.user.username), {
        onFinish: () => notificationForm.value?.endSubmit(),
        onSuccess: () => emit("completed"),
    });
};
</script>
