<template>
    <VueForm
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="$route('tech-tips.settings.edit')"
        submit-method="put"
        submit-text="Update Tip Settings"
    >
        <!-- <TextInput id="input" name="input" label="Input" focus /> -->
        <div class="d-flex justify-content-center mb-2">
            <div>
                <CheckboxSwitch
                    id="comments"
                    name="comments"
                    label="Allow Comments on Tech Tips"
                />
                <CheckboxSwitch
                    id="public"
                    name="public"
                    label="Allow Public Tech Tips"
                />
            </div>
        </div>
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import CheckboxSwitch from "../_Base/CheckboxSwitch.vue";
import { object, boolean } from "yup";

const props = defineProps<{
    settings?: any;
}>();

const initValues = {};
const schema = object({});
</script>
