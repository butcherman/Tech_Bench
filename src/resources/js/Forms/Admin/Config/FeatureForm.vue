<template>
    <VueForm
        ref="form"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="$route('admin.features.update')"
        submit-method="put"
        submit-text="Update Features"
    >
        <div class="flex place-content-center">
            <div>
                <SwitchInput
                    id="file_links"
                    name="file_links"
                    label="File Links"
                    help="File Links allow users to share files via a unique
                          download and/or upload link."
                />
                <SwitchInput
                    id="public_tips"
                    name="public_tips"
                    label="Public Tech Tips"
                    help="Public Tech Tips are Knowledge Base articles that
                          can be accessed by non-registered visitors to the
                          Tech Bench."
                />
                <SwitchInput
                    id="tip_comments"
                    name="tip_comments"
                    label="Tech Tip Comments"
                    help="Allow or disallow Commenting on internal Tech Tips."
                />
            </div>
        </div>
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import SwitchInput from "@/Forms/_Base/SwitchInput.vue";
import { object, boolean } from "yup";

const props = defineProps<{
    featureList: {
        [key: string]: boolean;
    };
}>();

const initValues = {
    file_links: props.featureList.file_links,
    public_tips: props.featureList.public_tips,
    tip_comments: props.featureList.tip_comments,
};
const schema = object({
    file_links: boolean().required(),
    public_tips: boolean().required(),
    tip_comments: boolean().required(),
});
</script>
