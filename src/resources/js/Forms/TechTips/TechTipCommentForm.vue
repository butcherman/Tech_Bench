<template>
    <VueForm
        ref="tipCommentForm"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="$route('tech-tips.comments.store', tipSlug)"
        submit-method="post"
        submit-text="Add Comment"
        @success="handleSuccess"
    >
        <TextAreaInput
            id="comment"
            name="comment"
            placeholder="Join the Discussion..."
        />
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import TextAreaInput from "../_Base/TextAreaInput.vue";
import { ref } from "vue";
import { object, string } from "yup";

defineProps<{
    tipSlug: string;
}>();

const tipCommentForm = ref<InstanceType<typeof VueForm> | null>(null);

const initValues = {
    comment: "",
};
const schema = object({
    comment: string().required(),
});

const handleSuccess = () => {
    tipCommentForm.value?.resetForm();
};
</script>
