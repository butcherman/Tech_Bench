<template>
    <VueFileForm
        ref="logoForm"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="$route('admin.logo.update')"
        submit-text="Upload Logo"
        file-required
        :accepted-files="['image/*']"
        @success="onSuccess"
    />
</template>

<script setup lang="ts">
import VueFileForm from "@/Forms/_Base/VueFileForm.vue";
import { ref } from "vue";
import { router } from "@inertiajs/vue3";
import { object } from "yup";
import { useAppStore } from "@/Store/AppStore";

const emit = defineEmits(["success"]);
const logoForm = ref<InstanceType<typeof VueFileForm> | null>(null);
const appStore = useAppStore();
const initValues = {};
const schema = object({});

const onSuccess = () => {
    router.reload();
    logoForm.value?.resetForm();
    appStore.pushFlashMsg({
        type: "success",
        message: "Logo Saved Successfully",
    });
    emit("success");
};
</script>
