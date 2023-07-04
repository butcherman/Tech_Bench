<template>
    <VueForm
        ref="verificationForm"
        id="verification-form"
        :initial-values="initValues"
        :validation-schema="schema"
        submit-text="Verify"
        @submit="onSubmit"
    >
        <TextInput id="code" name="code" label="Verification Code" focus />
        <CheckboxSwitch v-if="allowRemember" id="remember-device" name="remember" label="Remember This Device" class="mb-3" />
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import CheckboxSwitch from "../_Base/CheckboxSwitch.vue";
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import { shake } from '@/Modules/Animation.module';
import { object, string } from "yup";

defineProps<{
    allowRemember: boolean;
}>();

const verificationForm = ref<InstanceType<typeof VueForm> | null>(null);
const initValues = {
    code: "",
};
const schema = object({
    code: string().required(),
});

const onSubmit = (form: { code: string }) => {
    const formData = useForm(form);

    formData.post(route("2fa.store"), {
        onFinish: () => verificationForm.value?.endSubmit(),
        onError: () => shake(document.getElementById("verification-form")!!),
    });
};
</script>
