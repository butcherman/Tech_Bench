<template>
    <VueFileForm
        :initial-values="{}"
        :validation-schema="{}"
        :submit-route="$route('links.add-file', linkId)"
        :max-files="5"
        submit-text="Add File"
        file-required
        @success="$emit('success')"
    >
    </VueFileForm>
</template>

<script setup lang="ts">
import VueFileForm from "@/Forms/_Base/VueFileForm.vue";

defineEmits(["success"]);
defineProps<{
    linkId: number;
}>();
</script>
