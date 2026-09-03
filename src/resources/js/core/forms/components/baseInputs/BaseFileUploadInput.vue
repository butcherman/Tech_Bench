<script setup lang="ts">
import { computed, ref, useTemplateRef } from "vue";
import { useTusUpload } from "../../composables/tusUpload";

const props = defineProps<{
    purpose: string;

    autoUpload?: boolean;
    uploadMessage?: string;
}>();

const {
    error,
    uploading,
    progress,
    cancelUpload,
    startUpload,
    addFile,
    fileQueue,
} = useTusUpload();

const fileInput = useTemplateRef("file-input");

const uploadFileMessage = computed(
    () => props.uploadMessage ?? "Drag file here, or click to upload",
);

/**
 * Open the Select File dialog
 */
function selectFile(): void {
    fileInput.value?.click();
}

/**
 * Add the selected file to the upload queue
 */
function handleFileSelected(event: Event): void {
    const input = event.target as HTMLInputElement;
    const file = input.files?.[0];

    if (!file) {
        return;
    }

    addFile(file);
}

/**
 * Upload the files to the server
 */
const processQueue = () => {
    startUpload();
};

/*
|-------------------------------------------------------------------------------
| Handle Drag Events
|-------------------------------------------------------------------------------
*/
const dragging = ref(false);

function handleDragEnter(): void {
    dragging.value = true;
}

function handleDragLeave(): void {
    dragging.value = false;
}

function handleDrop(event: DragEvent): void {
    dragging.value = false;

    const file = event.dataTransfer?.files[0];

    if (!file) {
        return;
    }

    addFile(file);
}

/*
|-------------------------------------------------------------------------------
| Expose the queue trigger
|-------------------------------------------------------------------------------
*/
defineExpose({
    processQueue,
});
</script>

<template>
    <div class="space-y-6">
        <div>
            <input
                ref="file-input"
                type="file"
                accept="image/jpeg,image/png,image/gif,image/bmp"
                class="hidden"
                @change="handleFileSelected"
            />

            <div
                class="border-2 border-blue-300 border-dashed rounded-lg p-8 text-center cursor-pointer bg-blue-400/30"
                :class="{
                    'border-blue-600 bg-blue-400/80': dragging,
                }"
                @click="selectFile"
                @dragenter.prevent="handleDragEnter"
                @dragover.prevent
                @dragleave.prevent="handleDragLeave"
                @drop.prevent="handleDrop"
            >
                <input
                    ref="fileInput"
                    type="file"
                    accept="image/jpeg,image/png,image/gif,image/bmp"
                    class="hidden"
                    @change="handleFileSelected"
                />
                <div class="pointer-events-none">
                    <slot name="upload-message">
                        <fa-icon icon="cloud-arrow-up" />
                        {{ uploadFileMessage }}
                    </slot>
                </div>
            </div>
        </div>
        <div>
            {{ fileQueue }}
        </div>

        <div v-if="uploading" class="space-y-2">
            <div class="flex justify-between text-sm">
                <span>Uploading...</span>
                <span>{{ progress }}%</span>
            </div>
            <progress :value="progress" max="100" class="w-full" />
            <button type="button" @click="cancelUpload">Cancel</button>
        </div>

        <div v-if="error" class="text-sm text-red-600">
            {{ error }}
        </div>
    </div>
</template>
