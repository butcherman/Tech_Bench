<template>
    <VueForm
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="$route('2fa.update')"
        submit-method="put"
        submit-text="Verify"
    >
        <OtpInput id="code" name="code" focus />
        <SwitchInput
            v-if="allowRemember"
            id="remember-device"
            name="remember"
            label="Remember This Device"
            center
        />
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import OtpInput from "../_Base/OtpInput.vue";
import { object, string, boolean } from "yup";
import SwitchInput from "../_Base/SwitchInput.vue";

defineProps<{
    allowRemember: boolean;
}>();

/*
|---------------------------------------------------------------------------
| Validation
|---------------------------------------------------------------------------
*/
const initValues = {
    code: null,
    remember: false,
};
const schema = object({
    code: string().required(),
    remember: boolean().required(),
});
</script>
