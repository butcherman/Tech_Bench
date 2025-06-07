<script setup lang="ts">
import OtpInput from "../_Base/OtpInput.vue";
import SwitchInput from "../_Base/SwitchInput.vue";
import VueForm from "@/Forms/_Base/VueForm.vue";
import { object, string, boolean } from "yup";

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
    code: string().required("A Code is Required to Continue"),
    remember: boolean().required(),
});
</script>

<template>
    <VueForm
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="$route('2fa.update')"
        submit-method="put"
        submit-text="Verify"
        full-page-overlay
    >
        <OtpInput id="code" name="code" :length="6" focus />
        <SwitchInput
            v-if="allowRemember"
            id="remember-device"
            name="remember"
            label="Remember This Device"
            center
        />
    </VueForm>
</template>
