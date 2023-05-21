<template>
    <div id="dropzone-container" class="dropzone">
        <div class="dz-message">
            <fa-icon icon="fa-cloud-arrow-up" />
            Drag file here or click to upload
        </div>
        <div id="preview-template-container" class="d-none">
            <div class="dz-preview-wrapper">
                <div class="dz-preview">
                    <div class="dz-preview dz-file-preview">
                        <div class="dz-image">
                            <img data-dz-thumbnail />
                            <span class="img-attribute" />
                        </div>
                        <div class="dz-details">
                            <div class="dz-size" data-dz-size></div>
                            <div class="dz-filename">
                                <span data-dz-name></span>
                            </div>
                        </div>
                        <span class="dz-remove dz-tooltip" data-dz-remove>
                            <fa-icon icon="fa-trash-alt" />
                            <span class="dz-tooltip-text">Remove File</span>
                        </span>
                        <div class="dz-progress">
                            <span
                                class="dz-upload"
                                data-dz-uploadprogress
                            ></span>
                        </div>
                        <div class="dz-success-mark">
                            <fa-icon icon="fa-check" />
                        </div>
                        <div class="dz-error-mark">
                            <fa-icon icon="fa-exclamation-circle" />
                        </div>
                        <div class="dz-error-message">
                            <span data-dz-errormessage />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div v-if="errMessage" class="dz-validation-message">{{ errMessage }}</div>
</template>

<script setup lang="ts">
import Dropzone from "dropzone";
import { ref, onMounted, computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import { getFileIcon } from "@/Modules/fileIcon.module";
import type { fileDataType } from "@/Types";
import type { Ref } from "vue";

/**
 * Additional Styling for Drag and Drop
 */
import "file-icon-vectors/dist/file-icon-vectors.min.css";
import "dropzone/dist/basic.css";
import "../../../../scss/dropzoneInput.scss";

const emit = defineEmits([
    "file-added",
    "file-removed",
    "max-files-reached",
    "max-files-exceeded",
    "upload-progress",
    "total-upload-progress",
    "error",
    "success",
    "queue-complete",
    "complete",
]);
const props = defineProps<{
    uploadUrl: string;
    paramName: string;
    method?: "POST" | "PUT";
    maxFiles?: number;
    acceptedFiles?: string[];
    required?: boolean;
}>();
const fileData = computed<fileDataType>(() => usePage().props.app.fileData);
const errMessage: Ref<string | null> = ref(null);
const isTouched: Ref<boolean> = ref(false);
let myDrop: Dropzone;

/**
 * Upload the valid files
 */
const process = () => {
    myDrop.processQueue();
};

/**
 * Reset Dropzone to its initial state
 */
const reset = () => {
    myDrop.removeAllFiles();
}

/**
 * Validate the field by making sure there are no errors
 */
const validate = () => {
    //  If the field is required
    if (props.required) {
        if (
            myDrop.getQueuedFiles().length &&
            !myDrop.getRejectedFiles().length
        ) {
            errMessage.value = null;
            return true;
        }

        //  If validation failed, get an error message to display to the user
        if (props.required && myDrop.getRejectedFiles().length) {
            errMessage.value =
                "At least one error occurred.  Hover over the file(s) to view the error";
        } else {
            errMessage.value = "You must select a file to upload";
        }

        return false;
    }

    //  If the field is not required, verify there are no errors
    if (!myDrop.getRejectedFiles.length) {
        errMessage.value = null;
        return true;
    }

    errMessage.value =
        "At least one error occurred.  Hover over the file(s) to view the error";
    return false;
};

/**
 * Initialize the Dropzone Input
 */
onMounted(() => {
    /**
     * Grab the template for adding new files to the dropzone container
     */
    let previewNode = document.getElementById("preview-template-container");
    let previewTemplate = previewNode?.innerHTML;
    previewNode?.remove();

    /**
     * Initialize Dropzone
     */
    myDrop = new Dropzone("div#dropzone-container", {
        acceptedFiles: props.acceptedFiles
            ? props.acceptedFiles.join(", ")
            : undefined,
        addRemoveLinks: false,
        autoProcessQueue: false,
        //  FIXME  -  Chunking eats up memory???
        // chunking: true,
        // chunkSize: fileData.value.chunkSize,
        headers: { "X-CSRF-TOKEN": fileData.value.token },
        maxFiles: props.maxFiles || 5,
        maxFilesize: fileData.value.maxSize,
        method: props.method || "POST",
        parallelChunkUploads: true,
        paramName: props.paramName || "file",
        previewTemplate: previewTemplate,
        retryChunks: true,
        url: props.uploadUrl,
    });

    /**
     * Set Event Listeners and emits
     */
    myDrop.on("addedfile", (file) => {
        //  Note Dropzone has been touched/interacted with
        isTouched.value = true;

        //  If this is not an image file, see if we have an icon available
        const mime = file.type.split("/");
        if (mime[0] !== "image") {
            const ext = file.name.split(".").pop();
            if (ext) {
                const icon = getFileIcon(ext);
                if (icon) {
                    const imgWrapper =
                        file.previewElement.getElementsByClassName(
                            "dz-image"
                        )[0];
                    imgWrapper.getElementsByTagName("img")[0].src = icon.srcUrl;

                    if (icon.attribute) {
                        imgWrapper.getElementsByTagName("span")[0].innerHTML =
                            icon.attribute;
                    }
                }
            }
        }

        emit("file-added", file);
    });
    myDrop.on("removedfile", (file) => {
        validate();
        emit("file-removed", file);
    });
    myDrop.on("maxfilesreached", () => {
        emit("max-files-reached");
    });
    myDrop.on("maxfilesexceeded", () => {
        emit("max-files-exceeded");
    });

    myDrop.on("uploadprogress", (file, progress, bytesSent) => {
        emit("upload-progress", { file, progress, bytesSent });
    });
    myDrop.on("totaluploadprogress", (progress, totalBytes, bytesSent) => {
        emit("total-upload-progress", { progress, totalBytes, bytesSent });
    });

    myDrop.on("error", (file, message) => {
        emit("error", { file, message });
    });
    myDrop.on("success", (file, response) => {
        emit("success", { file, response });
    });
    myDrop.on("complete", (file) => {
        emit("complete", file);
    });
    myDrop.on("queuecomplete", () => {
        emit("queue-complete");
    });
});

defineExpose({ process, validate, reset, isTouched });
</script>
