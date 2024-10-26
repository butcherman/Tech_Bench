<template>
    <VueForm
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="$route('admin.tech-tips.settings.edit')"
        submit-method="put"
        submit-text="Update Tip Settings"
    >
        <div class="d-flex justify-content-center mb-2">
            <div>
                <CheckboxSwitch
                    id="comments"
                    name="allow_comments"
                    label="Allow Comments on Tech Tips"
                    help="Determines if a user can comment on a Tech Tip.  Once 
                          enabled globally, Administrators can enable and disable 
                          commenting based on the users Role."
                />
                <CheckboxSwitch
                    id="public"
                    name="allow_public"
                    label="Allow Public Tech Tips"
                    help="Once enabled, users with the proper permission can make 
                          a Tech Tip available to the general public."
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
    settings: {
        allow_comments: boolean;
        allow_public: boolean;
    };
}>();

const initValues = {
    allow_comments: props.settings.allow_comments,
    allow_public: props.settings.allow_public,
};
const schema = object({
    allow_comments: boolean().required(),
    allow_public: boolean().required(),
});
</script>
