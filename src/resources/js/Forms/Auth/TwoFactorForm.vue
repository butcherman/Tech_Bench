<template>
    <VueForm
        ref="form"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="$route('2fa.update')"
        submit-method="put"
        submit-text="Verify"
    >
        <TextInput id="code" name="code" label="Verification Code" focus />
        <div class="text-center mb-3">
            <CheckboxSwitch
                v-if="allowRemember"
                id="remember-device"
                name="remember"
                label="Remember This Device"
                inline
            />
        </div>
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import CheckboxSwitch from "@/Forms/_Base/CheckboxSwitch.vue";
import { boolean, object, string } from "yup";

defineProps<{
    allowRemember: boolean;
}>();
const initValues = {
    code: null,
    remember: false,
};
const schema = object({
    code: string().required(),
    remember: boolean().required(),
});
</script>
