<template>
    <div>
        <div class="dropzone pb-4" id="dropzone-container">
            <div class="dz-message">
                <fa-icon icon="cloud-arrow-up" />
                {{ message || "Drag file here or click to upload" }}
            </div>
        </div>
        <DropzonePreviewContainer id="preview-template-container" />
        <div v-if="errMessage" class="dz-validation-message">
            {{ errMessage }}
        </div>
    </div>
</template>

<script setup lang="ts">
import Dropzone from "dropzone";
import DropzonePreviewContainer from "./DropzonePreviewContainer.vue";
import axios from "axios";
import { ref, onMounted, computed, nextTick, unref } from "vue";
import { usePage } from "@inertiajs/vue3";
import { processFileIcon } from "@/Modules/FileIcons.module";
import type { Router } from "ziggy-js";

/**
 * Additional Styling for Drag and Drop
 */
import "file-icon-vectors/dist/file-icon-vectors.min.css";
import "dropzone/dist/basic.css";
import "@/../scss/dropzoneInput.scss";

const emit = defineEmits([
    "error",
    "file-added",
    "file-removed",
    "max-files-exceeded",
    "max-files-reached",
    "sending",
    "total-upload-progress",
    "upload-progress",
    "success",
]);
const props = defineProps<{
    uploadUrl: string | Router;
    paramName?: string;
    maxFiles?: number;
    message?: string;
    required?: boolean;
    acceptedFiles?: string[];
}>();

onMounted(() => {
    initDropzone();
    buildEventListeners();
});

/*******************************************************************************
 * Variables
 *******************************************************************************/
let myDrop: Dropzone;
let fileFormData: { [key: string]: any };

const fileData: fileData = usePage<pageProps>().props.app.fileData;
const isDirty = ref<boolean>(false);
const overMaxFiles = ref<boolean>(false);
const errMessage = ref<string | null>(null);
const completeMessage = ref<string | null>(null);

const acceptedFiles = computed(() =>
    props.acceptedFiles ? props.acceptedFiles.join(", ") : undefined
);

/*******************************************************************************
 * Initialize Dropzone Component
 *******************************************************************************/
const initDropzone = () => {
    // Grab the template for adding new files to the dropzone container
    let previewNode = document.getElementById("preview-template-container");
    let previewTemplate = previewNode?.innerHTML;
    previewNode?.remove();

    // Initialize Dropzone
    myDrop = new Dropzone("div#dropzone-container", {
        acceptedFiles: acceptedFiles.value,
        addRemoveLinks: false,
        autoProcessQueue: false,
        chunking: true,
        chunkSize: fileData.chunkSize,
        headers: { "X-CSRF-TOKEN": fileData.token },
        maxFiles: props.maxFiles || 5,
        maxFilesize: fileData.maxSize,
        method: "POST",
        parallelUploads: 1,
        parallelChunkUploads: false,
        paramName: props.paramName || "file",
        previewTemplate: previewTemplate,
        retryChunks: true,
        url: props.uploadUrl.toString(),
    });
};

/*******************************************************************************
 * Initialize Dropzone Event Listeners
 *******************************************************************************/
const buildEventListeners = () => {
    // Turn AutoProcessQueue back on so multiple files can be uploaded
    myDrop.on("processing", () => (myDrop.options.autoProcessQueue = true));

    // When a file is added to the queue
    myDrop.on("addedfile", (file) => {
        //  Note Dropzone has been touched/interacted with
        isDirty.value = true;
        processFileIcon(file);
        nextTick(() => {
            validate();
            emit("file-added", file);
        });
    });

    // When a file is removed from the queue
    myDrop.on("removedfile", (file) => {
        // Reprocess any rejected files
        let rejected = myDrop.getRejectedFiles();
        rejected.forEach((file) => myDrop.removeFile(file));
        rejected.forEach((file) => myDrop.addFile(file));

        nextTick(() => {
            validate();
            emit("file-removed", file);
        });
    });

    // When the maximum number of files has been reached
    myDrop.on("maxfilesreached", () => {
        emit("max-files-reached");
    });

    // When the maximum number of files has been exceeded
    myDrop.on("maxfilesexceeded", () => {
        overMaxFiles.value = true;
        emit("max-files-exceeded");
    });

    // Append Form Data to each File Chunk
    myDrop.on("sending", (file, xhr, formData) => {
        for (const field in fileFormData) {
            formData.append(
                field,
                // JSON.stringify(formData[field as keyof object])
                fileFormData[field]
            );
        }
        emit("sending", file, xhr, formData);
    });

    // Bubble Upload Progress
    myDrop.on("uploadprogress", (file, progress, bytesSent) => {
        emit("upload-progress", { file, progress, bytesSent });
    });

    // Bubble Total Upload Progress
    myDrop.on("totaluploadprogress", (progress, totalBytes, bytesSent) => {
        emit("total-upload-progress", { progress, totalBytes, bytesSent });
    });

    // Bubble any Errors that occur with Dropzone
    myDrop.on("error", (file, message) => {
        console.log("error", file, message);
        // emit("error", { file, message });
        emit("error", { file, status: file.xhr?.status, message });
    });

    // When a file completes its upload, we will store the latest response
    myDrop.on("complete", (file) => {
        completeMessage.value = file.xhr?.response;
    });

    // When all files have uploaded, we will emit success with the last complete message
    myDrop.on("queuecomplete", () => {
        console.log("queue complete");
        // isSubmitting.value = false;
        emit("success", completeMessage.value);
    });
};

/*******************************************************************************
 * Validate the dropzone field by making sure there are no errors
 *******************************************************************************/
const validate = () => {
    console.log("validating");

    // If any files were rejected by Dropzone, we trigger an error
    let rejected = myDrop.getRejectedFiles();
    let queued = myDrop.getAcceptedFiles();

    if (rejected.length) {
        // If there are too many files, we will say so
        if (overMaxFiles.value) {
            errMessage.value = `Maximum number of files exceeded.  Only ${props.maxFiles} files are allowed.`;
            return false;
        }

        // Else, have user hover over each file to see specific error
        errMessage.value =
            "At least one error occurred.  Hover the file(s) to view errors";
        return false;
    }

    // If the file is required, but not selected, we trigger an error
    if (props.required && !queued.length) {
        errMessage.value = "You must select a file to upload";
        return false;
    }

    // All Validation Passed.  Clear any error messages
    console.log("passed validation");
    errMessage.value = null;
    overMaxFiles.value = false;
    return true;
};

/*******************************************************************************
 * Process and Upload all files along with Form Data
 *******************************************************************************/
const process = (form: { [key: string]: any }) => {
    console.log("processing", form);

    fileFormData = form;

    // If no files are present, we do an axios submission
    if (myDrop.files.length === 0) {
        console.log("no file");
        axios
            .post(props.uploadUrl.toString(), fileFormData)
            .then((res) => emit("success", res.data))
            .catch((err) =>
                emit("error", {
                    file: [],
                    status: err.response.status,
                    message: err.response.data,
                })
            );
    } else {
        console.log("has a file");
        myDrop.processQueue();
    }
};

/*******************************************************************************
 * Reset Dropzone to its initial state
 *******************************************************************************/
const reset = () => {
    myDrop.removeAllFiles();
    errMessage.value = null;
    isDirty.value = false;
};

/*******************************************************************************
 * Cancel the upload process and remove all files from queue
 *******************************************************************************/
const cancelUpload = () => {
    const fileList = myDrop.files;
    fileList.forEach((file) => myDrop.cancelUpload(file));
};

defineExpose({
    process,
    reset,
    cancelUpload,
    validate,
    isDirty: unref(isDirty),
});
</script>
