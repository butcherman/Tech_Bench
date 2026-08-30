<script setup lang="ts">
import SwitchInput from "@/core/forms/components/validatedInputs/SwitchInput.vue";
import VueForm from "@/core/forms/components/VueForm.vue";
import { object, boolean } from "yup";
import { update } from "@/wayfinder/routes/admin/features";

defineEmits<{
    success: [];
}>();

const props = defineProps<{
    featureList: AppFeatureList;
}>();

const initValues = {
    file_links: props.featureList.file_links,
    enable_workbooks: props.featureList.enable_workbooks,
    public_tips: props.featureList.public_tips,
    tip_comments: props.featureList.tip_comments,
};

const schema = object({
    file_links: boolean().required(),
    enable_workbooks: boolean().required(),
    public_tips: boolean().required(),
    tip_comments: boolean().required(),
});
</script>

<template>
    <VueForm
        name="app-feature-form"
        submit-method="put"
        submit-text="Update Features"
        :initial-values="initValues"
        :submit-route="update.url()"
        :validation-schema="schema"
        @success="$emit('success')"
        do-not-reset
    >
        <div class="flex justify-center">
            <div class="flex flex-col items-center gap-1">
                <fieldset
                    class="border border-slate-300 rounded-lg flex flex-col gap-3 p-2"
                >
                    <legend>General</legend>
                    <SwitchInput
                        name="file_links"
                        label="File Links"
                        help-message="File Links allow users to share files via a unique
                    download and/or upload link."
                        help-visible
                    />
                </fieldset>
                <fieldset
                    class="border border-slate-300 rounded-lg flex flex-col gap-3 p-2"
                >
                    <legend>Customers</legend>
                    <SwitchInput
                        name="enable_workbooks"
                        label="Customer Onboarding Workbooks"
                        help-message="Customizable workbooks to help gather data from
                    the customer for installing and configuring new equipment"
                        help-visible
                    />
                </fieldset>
                <fieldset
                    class="border border-slate-300 rounded-lg flex flex-col gap-3 p-2"
                >
                    <legend>Tech Tips</legend>
                    <SwitchInput
                        name="public_tips"
                        label="Public Tech Tips"
                        help-message="Public Tech Tips are Knowledge Base articles that
                    can be accessed by non-registered visitors to the
                    Tech Bench."
                        help-visible
                    />
                    <SwitchInput
                        name="tip_comments"
                        label="Tech Tip Comments"
                        help-message="Allow or disallow Commenting on internal Tech Tips."
                        help-visible
                    />
                </fieldset>
            </div>
        </div>
    </VueForm>
</template>
