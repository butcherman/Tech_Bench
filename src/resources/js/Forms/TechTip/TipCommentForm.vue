<script setup lang="ts">
import TextAreaInput from "../_Base/TextAreaInput.vue";
import VueForm from "@/Forms/_Base/VueForm.vue";
import { computed } from "vue";
import { object, string } from "yup";

const props = defineProps<{
    comment?: techTipComment;
    techTip: techTip;
}>();

const commentLabel = computed(() =>
    props.comment ? "Edit Comment" : "Join the Discussion"
);

const submitRoute = computed(() =>
    props.comment
        ? route("tech-tips.comments.update", [
              props.techTip.slug,
              props.comment.comment_id,
          ])
        : route("tech-tips.comments.store", props.techTip.slug)
);
const submitMethod = computed(() => (props.comment ? "put" : "post"));
const submitText = computed(() =>
    props.comment ? "Edit Comment" : "Create Comment"
);

/*
|-------------------------------------------------------------------------------
| Vee Validate
|-------------------------------------------------------------------------------
*/
const initValues = {
    comment_data: props.comment?.comment || "",
};
const schema = object({
    comment_data: string().required("A Comment is Required"),
});
</script>

<template>
    <VueForm
        :initial-values="initValues"
        :submit-method="submitMethod"
        :submit-route="submitRoute"
        :submit-text="submitText"
        :validation-schema="schema"
    >
        <TextAreaInput id="comment" name="comment_data" :label="commentLabel" />
    </VueForm>
</template>
