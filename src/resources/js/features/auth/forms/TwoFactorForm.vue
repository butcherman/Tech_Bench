<script setup lang="ts">
import OtpInput from "@/core/forms/components/validatedInputs/OtpInput.vue";
import SwitchInput from "@/core/forms/components/validatedInputs/SwitchInput.vue";
import VueForm from "@/core/forms/components/VueForm.vue";
import { object, string, boolean } from "yup";

defineEmits<{
    success: [];
}>();

const props = defineProps<{
    allowRemember: boolean;
    submitRoute: string;
}>();

const initValues = {
    code: "",
    remember_device: false,
};

const schema = object({
    code: string().required("A Code is Required to Continue"),
    remember_device: boolean().required(),
});
</script>

<template>
    <VueForm
        name="two-factor-form"
        submit-method="post"
        submit-text="Verify"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="submitRoute"
        full-page-overlay
        @success="$emit('success')"
    >
        <OtpInput name="code" :length="6" v-focus />
        <div v-if="allowRemember" class="flex justify-center">
            <div>
                <SwitchInput
                    name="remember_device"
                    label="Remember this device"
                    v-focus
                />
            </div>
        </div>
    </VueForm>
</template>
