<template>
    <VueFileForm
        :initial-values="{}"
        :validation-schema="{}"
        :submit-route="$route('links.add-file', linkId)"
        :max-files="5"
        submit-text="Add File"
        file-required
        @success="handleSuccess"
    >
    </VueFileForm>
</template>

<script setup lang="ts">
import VueFileForm from "@/Forms/_Base/VueFileForm.vue";
import { useForm } from "@inertiajs/vue3";

const emit = defineEmits(["success"]);
const props = defineProps<{
    linkId: number;
}>();

const handleSuccess = () => {
    let formData = useForm({});
    formData.post(route("links.add-file", props.linkId));

    emit("success");
};
</script>
