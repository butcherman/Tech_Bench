<script setup lang="ts">
import SwitchInput from "../_Base/SwitchInput.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import VueForm from "@/Forms/_Base/VueForm.vue";
import { boolean, object, string } from "yup";
import { growShow, shrinkHide } from "@/Composables/animations.module";
import { ref } from "vue";

const props = defineProps<{
    allow_comments: boolean;
    allow_download: boolean;
    allow_public: boolean;
    public_link_text: string;
}>();

const showPublicLink = ref<boolean>(props.allow_public);

/*
|-------------------------------------------------------------------------------
| Vee Validate
|-------------------------------------------------------------------------------
*/
const initValues = {
    allow_comments: props.allow_comments,
    allow_download: props.allow_download,
    allow_public: props.allow_public,
    public_link_text: props.public_link_text,
};
const schema = object({
    allow_comments: boolean().required(),
    allow_download: boolean().required(),
    allow_public: boolean().required(),
    public_link_text: string().required(),
});
</script>

<template>
    <VueForm
        submit-method="put"
        submit-text="Update Tech Tip Settings"
        :initial-values="initValues"
        :submit-route="$route('admin.tech-tips.settings.update')"
        :validation-schema="schema"
    >
        <div class="flex justify-center">
            <div>
                <SwitchInput
                    id="allow-comments"
                    name="allow_comments"
                    label="Allow Comments"
                />
                <SwitchInput
                    id="allow-download"
                    name="allow_download"
                    label="Allow Download Tech Tip as PDF"
                />
                <SwitchInput
                    id="allow-public"
                    name="allow_public"
                    label="Allow Public Tech Tips"
                    @change="showPublicLink = !showPublicLink"
                />
            </div>
        </div>
        <Transition @enter="growShow" @leave="shrinkHide">
            <div v-show="showPublicLink">
                <TextInput
                    id="public-link-text"
                    name="public_link_text"
                    label="Text for Public Tips on Home Page"
                    help="This text will display as the link to the public Tech Tip page"
                />
            </div>
        </Transition>
    </VueForm>
</template>
