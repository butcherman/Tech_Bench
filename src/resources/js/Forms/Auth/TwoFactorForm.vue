<script setup lang="ts">
import { computed } from "vue";
import OtpInput from "../_Base/OtpInput.vue";
import SwitchInput from "../_Base/SwitchInput.vue";
import VueForm from "@/Forms/_Base/VueForm.vue";
import { object, string, boolean } from "yup";

const props = defineProps<{
    allowRemember: boolean;
    via: "authenticator" | "email";
}>();

const submitRoute = computed(() => {
    switch (props.via) {
        case "authenticator":
            return route("two-factor.login.store");
        case "email":
            return route("two-factor.login.email");
    }
});

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
        :submit-route="submitRoute"
        submit-method="post"
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
