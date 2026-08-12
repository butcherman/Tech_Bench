<script setup lang="ts">
import BaseButton from "@/core/components/buttons/BaseButton.vue";
import BaseTextInput from "@/core/forms/components/baseInputs/BaseTextInput.vue";
import BaseVueForm from "@/core/forms/components/BaseVueForm.vue";
import Modal from "@/core/components/Modal.vue";
import SubmitButton from "@/core/components/buttons/SubmitButton.vue";
import { ref } from "vue";

const emit = defineEmits<{
    setLink: [{ url: string; text: string }];
    removeLink: [];
    hidden: [];
}>();

const props = defineProps<{
    text?: string;
    url?: string;
}>();

const showModal = ref(true);
const linkText = ref(props.text ?? "");
const linkUrl = ref(props.url ?? "");

const onSubmitLink = () => {
    emit("setLink", { text: linkText.value, url: linkUrl.value });
    showModal.value = false;
};

const onRemoveLink = () => {
    emit("removeLink");
    showModal.value = false;
};
</script>

<template>
    <Modal v-model:show="showModal" size="sm" @hidden="$emit('hidden')">
        <BaseVueForm
            name="editor-link-data"
            class="flex flex-col gap-2"
            @submit="onSubmitLink"
        >
            <BaseTextInput
                v-model:value="linkText"
                name="link_text"
                label="Link Text"
            />
            <BaseTextInput
                v-model:value="linkUrl"
                name="link_dest"
                label="Link Destination"
            />
            <template #submit-button>
                <div class="flex flex-row-reverse gap-2">
                    <SubmitButton text="Save Link" size="sm" />
                    <BaseButton
                        v-if="props.url"
                        variant="danger"
                        text="Remove Link"
                        size="sm"
                        @click="onRemoveLink"
                    />
                </div>
            </template>
        </BaseVueForm>
    </Modal>
</template>
