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
                        <div class="dz-image h-100">
                            <img data-dz-thumbnail />
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
import { ref, onMounted } from "vue";
import { usePage } from "@inertiajs/vue3";
import { getFileIcon } from "@/Modules/fileIcon.module";
import type { Ref } from "vue";

/**
 * Additional Styling for Drag and Drop
 */
import "file-icon-vectors/dist/file-icon-vectors.min.css";
import "dropzone/dist/basic.css";
import "../../../../scss/dropzoneInput.scss";
import "../../../../scss/extendedIconCatalog.scss";

const emit = defineEmits([
    "file-added",
    "file-removed",
    "max-files-reached",
    "max-files-exceeded",
    "upload-progress",
    "total-upload-progress",
    "error",
    "sending",
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

const page: pageData = usePage();
const fileData: fileData = page.props.app.fileData;
const errMessage: Ref<string | null> = ref(null);
const isTouched: Ref<boolean> = ref(false);
const isSubmitting = ref(false);

let myDrop: Dropzone;
let myFormData: object;

/**
 * Upload the valid files
 */
const process = (form = {}) => {
    myFormData = form;
    isSubmitting.value = true;
    myDrop.processQueue();
};

/**
 * Cancel the upload process and remove all files from queue
 */
const cancelUpload = () => {
    const fileList = myDrop.files;
    fileList.forEach((file) => myDrop.cancelUpload(file));
    isSubmitting.value = false;
};

/**
 * Reset Dropzone to its initial state
 */
const reset = () => {
    myDrop.removeAllFiles();
};

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
 * Initialize the Dropzone Input and add event listeners
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
        chunking: true,
        chunkSize: fileData.chunkSize,
        headers: { "X-CSRF-TOKEN": fileData.token },
        maxFiles: props.maxFiles || 5,
        maxFilesize: fileData.maxSize,
        method: props.method || "POST",
        parallelChunkUploads: false,
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
                let iconClass = getFileIcon(ext);
                let innerSpan = "";
                //  If not icon was found, insert the default class
                if (!iconClass) {
                    iconClass = "fiv-cla fiv-icon-blank fiv-size-xl";
                    innerSpan = `<span class="ext-identifier">{.${ext}}</span>`;
                }

                const imgWrapper =
                    file.previewElement.getElementsByClassName("dz-image")[0];
                imgWrapper.innerHTML = `<span class="${iconClass} w-100 h-100" />${innerSpan}`;
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

    myDrop.on("sending", (file, xhr, formData) => {
        for (const field in myFormData) {
            formData.append(field, myFormData[field as keyof object]);
        }
        emit("sending", file, xhr, formData);
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
        console.log("success", response);
        emit("success", { file, response });
    });
    myDrop.on("complete", (file) => {
        console.log("complete", file);
        emit("complete", file);
    });
    myDrop.on("queuecomplete", () => {
        console.log("queue complete");
        isSubmitting.value = false;
        emit("queue-complete");
    });
});

defineExpose({ process, cancelUpload, validate, reset, isTouched });
</script>
