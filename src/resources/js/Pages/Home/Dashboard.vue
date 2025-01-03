<template>
    <div class="text-center">
        <BaseButton text="Add Flash Message" class="m-2" @click="pushFlash" />
        <BaseButton text="Add Message Toast" class="m-2" />
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import { useAppStore } from "@/Stores/AppStore";
import { ref } from "vue";

const app = useAppStore();

const index = ref(0);
const pushFlash = () => {
    console.log("message pushed");
    app.pushFlashMsg({ type: "warning", message: `Message - ${index.value}` });
    index.value++;
};
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
