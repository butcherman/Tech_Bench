<script setup lang="ts">
import prettyBytes from "pretty-bytes";
import { computed, ref, useTemplateRef } from "vue";
import { useFileIconHelper } from "../../composables/fileIconHelper";
import { useTusUpload } from "../../composables/tusUpload";
import "file-icon-vectors/dist/file-icon-vectors.min.css";

const emit = defineEmits<{}>();

const props = defineProps<{
    purpose: string;

    autoUpload?: boolean;
    maxFiles?: number;
    uploadMessage?: string;
}>();

const {
    error,
    fileQueue,
    progress,
    uploading,
    addFile,
    cancelUpload,
    startUpload,
    removeFile,
} = useTusUpload();

const { getFileIcon } = useFileIconHelper();

const fileInput = useTemplateRef("fileInput");

const uploadFileMessage = computed(
    () => props.uploadMessage ?? "Drag file here, or click to upload",
);

const uploadFileLimit = computed(() => props.maxFiles ?? 1);

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
    const fileList = input.files;

    if (!fileList) {
        return;
    }

    for (let i = 0; i < fileList.length; i++) {
        let file = fileList[i];
        if (file) {
            addFile(file);
        }
    }

    // Auto start the queue if needed
    if (props.autoUpload) {
        startUpload();
    }
}

/**
 * Remove a file from the upload queue
 */
const onRemoveFile = (file: File): void => {
    console.log(file);
    removeFile(file);
};

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
                ref="fileInput"
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
                    :multiple="uploadFileLimit > 1"
                    @change="handleFileSelected"
                />
                <div
                    v-if="fileQueue.length"
                    class="dropzone-queue-wrapper flex gap-3 justify-center flex-wrap"
                >
                    <div v-for="queuedFile in fileQueue">
                        <div class="flex justify-center">
                            <div class="relative">
                                <fa-icon
                                    icon="trash-alt"
                                    class="text-danger absolute top-0 right-0 z-50 bg-slate-300"
                                    v-tooltip="'Remove File'"
                                    @click.stop="onRemoveFile(queuedFile.file)"
                                />
                                <span :class="getFileIcon(queuedFile.file)" />
                            </div>
                        </div>
                        <div class="text-xs text-muted">
                            {{ queuedFile.file.name }}
                        </div>
                        <div class="text-xs text-muted">
                            {{ prettyBytes(queuedFile.file.size) }}
                        </div>
                    </div>
                </div>
                <div v-else class="pointer-events-none">
                    <slot name="upload-message">
                        <fa-icon icon="cloud-arrow-up" />
                        {{ uploadFileMessage }}
                    </slot>
                </div>
            </div>
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
