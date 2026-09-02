<script setup lang="ts">
import * as tus from "tus-js-client";
import { onBeforeUnmount, ref } from "vue";

const props = defineProps<{
    currentLogo: string;
    isDefault: boolean;
}>();

const fileInput = ref<HTMLInputElement | null>(null);

const uploading = ref(false);
const progress = ref(0);
const errorRef = ref<string | null>(null);

let upload: tus.Upload | null = null;

function selectFile(): void {
    fileInput.value?.click();
}

function handleFileSelected(event: Event): void {
    const input = event.target as HTMLInputElement;
    const file = input.files?.[0];

    if (!file) {
        return;
    }

    errorRef.value = null;
    progress.value = 0;

    startUpload(file);
}

function startUpload(file: File): void {
    upload?.abort();

    uploading.value = true;

    upload = new tus.Upload(file, {
        endpoint: "/tus",
        chunkSize: 5_000_000,

        metadata: {
            name: file.name,
            type: file.type,
            purpose: "logo",
        },

        onError(error) {
            uploading.value = false;
            errorRef.value = error.message;
            console.log(error);
        },

        onProgress(bytesUploaded, bytesTotal) {
            progress.value = Math.round((bytesUploaded / bytesTotal) * 100);
        },

        onSuccess() {
            uploading.value = false;
            progress.value = 100;

            console.log("success");

            // We'll handle refreshing the logo here.
        },
    });

    upload.start();
}

function cancelUpload(): void {
    upload?.abort();
    upload = null;
    uploading.value = false;
    progress.value = 0;
}

onBeforeUnmount(() => {
    upload?.abort();
});
</script>

<template>
    <div>
        <input
            ref="fileInput"
            type="file"
            accept="image/jpeg,image/png,image/gif,image/bmp"
            class="hidden"
            @change="handleFileSelected"
        />

        <button type="button" :disabled="uploading" @click="selectFile">
            Choose Logo
        </button>

        <div v-if="uploading">
            Uploading: {{ progress }}%

            <button type="button" @click="cancelUpload">Cancel</button>
        </div>

        <div v-if="errorRef">
            {{ errorRef }}
        </div>
    </div>
</template>
