<template>
    <VueForm
        ref="tipCommentForm"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="submitRoute"
        :submit-method="submitMethod"
        :submit-text="submitText"
        @success="handleSuccess"
    >
        <TextAreaInput
            id="comment"
            name="comment_data"
            placeholder="Join the Discussion..."
        />
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import TextAreaInput from "../_Base/TextAreaInput.vue";
import { ref, computed } from "vue";
import { object, string } from "yup";

const emit = defineEmits(["success"]);
const props = defineProps<{
    tipSlug: string;
    commentData?: tipComment;
}>();

const tipCommentForm = ref<InstanceType<typeof VueForm> | null>(null);

const submitRoute = computed(() =>
    props.commentData
        ? route("tech-tips.comments.update", [
              props.tipSlug,
              props.commentData.comment_id,
          ])
        : route("tech-tips.comments.store", props.tipSlug)
);
const submitMethod = computed(() => (props.commentData ? "put" : "post"));
const submitText = computed(() =>
    props.commentData ? "Edit Comment" : "Create Comment"
);

const initValues = {
    comment_data: props.commentData?.comment || "",
};
const schema = object({
    comment_data: string().required(),
});

const handleSuccess = () => {
    tipCommentForm.value?.resetForm();
    emit("success");
};
</script>
