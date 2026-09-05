<script setup lang="ts">
import InputWrapper from "../wrappers/InputWrapper.vue";
import prettyBytes from "pretty-bytes";
import { computed, useTemplateRef } from "vue";
import { useFileIconHelper } from "../../composables/fileIconHelper.js";
import { useTusUpload } from "../../composables/tusUpload.js";
import { useUploadHelper } from "../../composables/uploadHelper.js";
import "file-icon-vectors/dist/file-icon-vectors.min.css";

const emit = defineEmits<{
    fileUploaded: [file: string];
    queueCompleted: [files: InputQueuedFile[]];
}>();

const props = defineProps<{
    purpose: string;

    acceptedFiles?: string[];
    autoUpload?: boolean;
    maxFiles?: number;
    uploadMessage?: string;
}>();

const { getFileIcon } = useFileIconHelper();
const { startUpload } = useTusUpload({
    onFileUploaded: (file) => {
        emit("fileUploaded", file);
    },

    onQueueCompleted: (files) => {
        emit("queueCompleted", files);
    },
});

const {
    dragging,
    fileQueue,
    rejectedFiles,
    totalProgress,

    onDragEnter,
    onDragLeave,
    onRemoveFile,
    processFileList,
} = useUploadHelper(props);

const fileInput = useTemplateRef("file-input");

const allowMultiple = computed<boolean>(() =>
    props.maxFiles && props.maxFiles > 1 ? true : false,
);

/**
 * Manually open the file upload dialog
 */
const selectFile = () => {
    fileInput.value?.click();
};

const onFileSelected = (event: Event): void => {
    const input = event.target as HTMLInputElement;
    const fileList = input.files;

    if (!fileList) {
        return;
    }

    processFileList(fileList);

    if (props.autoUpload) {
        console.log("process queue");
        startUpload(fileQueue, props.purpose);
    }
};

/**
 * File dropped into dropzone
 */
const onDrop = (event: DragEvent): void => {
    onDragLeave();

    const fileList = event.dataTransfer?.files;

    if (!fileList) {
        return;
    }

    processFileList(fileList);

    if (props.autoUpload) {
        console.log("process queue");
        startUpload(fileQueue, props.purpose);
    }
};
</script>

<template>
    <InputWrapper :has-focus="false">
        <slot :totalProgress />
        <div
            class="h-full border-2 border-blue-300 border-dashed rounded-lg py-8 text-center cursor-pointer bg-blue-400/30"
            :class="{
                'border-blue-600 bg-blue-400/80': dragging,
            }"
            @click="selectFile"
            @dragover.prevent
            @dragenter.prevent="onDragEnter"
            @dragleave.prevent="onDragLeave"
            @drop.prevent="onDrop"
        >
            <input
                ref="file-input"
                type="file"
                class="hidden"
                :multiple="allowMultiple"
                @change="onFileSelected"
            />
            <div
                v-if="fileQueue.length || rejectedFiles.length"
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
                            <div class="absolute top-1/2 w-full">
                                <div
                                    v-if="queuedFile.error"
                                    class="text-danger text-2xl"
                                >
                                    <fa-icon icon="circle-exclamation" />
                                </div>
                                <div
                                    v-else-if="
                                        queuedFile.status === 'uploading'
                                    "
                                    class="w-full bg-gray-200 rounded-full h-4 dark:bg-gray-700"
                                >
                                    <div
                                        class="bg-blue-600 h-4 rounded-full text-xs font-medium text-blue-100 text-center p-0.5 leading-none"
                                        :style="`width: ${queuedFile.progress}%`"
                                    >
                                        {{ queuedFile.progress }}%
                                    </div>
                                </div>
                                <div
                                    v-if="queuedFile.status === 'complete'"
                                    class="text-success text-2xl"
                                >
                                    <fa-icon icon="circle-check" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-if="queuedFile.error" class="text-xs text-danger">
                        {{ queuedFile.error }}
                    </div>

                    <div v-else>
                        <div class="text-xs text-muted">
                            {{ queuedFile.file.name }}
                        </div>
                        <div class="text-xs text-muted">
                            {{ prettyBytes(queuedFile.file.size) }}
                        </div>
                    </div>
                </div>

                <div v-for="rejected in rejectedFiles">
                    <div class="flex justify-center">
                        <div class="relative">
                            <fa-icon
                                icon="trash-alt"
                                class="text-danger absolute top-0 right-0 z-50 bg-slate-300"
                                v-tooltip="'Remove File'"
                                @click.stop="onRemoveFile(rejected.file)"
                            />
                            <span :class="getFileIcon(rejected.file)" />
                            <div class="absolute top-1/3 w-full">
                                <div class="text-warning text-2xl">
                                    <fa-icon icon="triangle-exclamation" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-xs text-danger">
                        {{ rejected.error }}
                    </div>
                </div>
            </div>
            <div v-else class="dropzone-message pointer-events-none h-full">
                <slot name="upload-message">
                    <div class="h-full flex flex-col justify-center">
                        <div>
                            <fa-icon icon="cloud-arrow-up" />
                            {{
                                uploadMessage ??
                                "Drag file here or click to upload"
                            }}
                        </div>
                    </div>
                </slot>
            </div>
        </div>
    </InputWrapper>
</template>
