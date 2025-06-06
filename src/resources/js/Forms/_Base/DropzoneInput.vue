<script setup lang="ts">
import Dropzone from "@deltablot/dropzone";
import { dataPost } from "@/Composables/axiosWrapper.module";
import { processFileIcon } from "@/Composables/fileIcons.module";
import { ref, onMounted, computed, nextTick } from "vue";
import { usePage } from "@inertiajs/vue3";
import type { Router } from "ziggy-js";
import type { DropzoneFile } from "dropzone";

/**
 * Additional Styling for Drag and Drop
 */
import "file-icon-vectors/dist/file-icon-vectors.min.css";
import "../../../css/icon_extended_library.css";
import "../../../css/dropzone.css";

const emit = defineEmits<{
    error: [
        {
            file?: DropzoneFile;
            status: number | undefined;
            message: laravelValidationErrors;
        }
    ];
    fileAdded: [DropzoneFile];
    fileRemoved: [DropzoneFile];
    maxFilesReached: [];
    maxFilesExceeded: [];
    sending: [DropzoneFile, XMLHttpRequest, FormData];
    success: [string | null];
    totalUploadProgress: [
        { progress: number; totalBytes: number; bytesSent: number }
    ];
    uploadProgress: [
        { file: DropzoneFile; progress: number; bytesSent: number }
    ];
}>();

const props = defineProps<{
    uploadUrl: string | Router;
    paramName?: string;
    maxFiles?: number;
    uploadMessage?: string;
    required?: boolean;
    acceptedFiles?: string[];
}>();

onMounted(() => {
    initDropzone();
    buildEventListeners();
});

/*
|-------------------------------------------------------------------------------
| Variables
|-------------------------------------------------------------------------------
*/
let myDrop: Dropzone;
let fileFormData: { [key: string]: any };

const fileData: fileData = appData.fileData;
const csrfToken: string = usePage<pageProps>().props.csrf_token;
const isDirty = ref<boolean>(false);
const overMaxFiles = ref<boolean>(false);
const errMessage = ref<string | null>(null);
const completeMessage = ref<string | null>(null);
const hasFile = ref<boolean>(false);

const acceptedFiles = computed<string | undefined>(() =>
    props.acceptedFiles ? props.acceptedFiles.join(", ") : undefined
);

/*
 |-------------------------------------------------------------------------------
 | Initialize Dropzone Component
 |-------------------------------------------------------------------------------
 */
const initDropzone = (): void => {
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
        headers: {
            "X-CSRF-TOKEN": csrfToken,
            "X-Socket-Id": Echo.socketId() ?? "",
        },
        maxFiles: props.maxFiles || 5,
        maxFilesize: fileData.maxSize / (1024 * 1024),
        method: "POST",
        parallelUploads: 1,
        parallelChunkUploads: false,
        paramName: props.paramName || "file",
        previewTemplate: previewTemplate,
        retryChunks: true,
        url: props.uploadUrl.toString(),
    });
};

/*
|-------------------------------------------------------------------------------
| Initialize Dropzone Event Listeners
|-------------------------------------------------------------------------------
*/
const buildEventListeners = (): void => {
    // Turn AutoProcessQueue back on so multiple files can be uploaded
    myDrop.on("processing", () => (myDrop.options.autoProcessQueue = true));

    // When a file is added to the queue
    myDrop.on("addedfile", (file: DropzoneFile): void => {
        //  Note Dropzone has been touched/interacted with
        isDirty.value = true;
        processFileIcon(file);
        nextTick(() => {
            validate();
            emit("fileAdded", file);
        });
    });

    // When a file is removed from the queue
    myDrop.on("removedfile", (file: DropzoneFile): void => {
        // Reprocess any rejected files
        let rejected = myDrop.getRejectedFiles();
        rejected.forEach((file) => myDrop.removeFile(file));
        rejected.forEach((file) => myDrop.addFile(file));

        nextTick(() => {
            validate();
            emit("fileRemoved", file);
        });
    });

    // When the maximum number of files has been reached
    myDrop.on("maxfilesreached", (): void => {
        emit("maxFilesReached");
    });

    // When the maximum number of files has been exceeded
    myDrop.on("maxfilesexceeded", (): void => {
        overMaxFiles.value = true;
        emit("maxFilesExceeded");
    });

    // Append Form Data to each File Chunk
    myDrop.on(
        "sending",
        (file: DropzoneFile, xhr: XMLHttpRequest, formData: FormData): void => {
            for (const [field, value] of Object.entries(fileFormData)) {
                formData.append(field, JSON.stringify(value));
            }

            emit("sending", file, xhr, formData);
        }
    );

    // Bubble Upload Progress
    myDrop.on(
        "uploadprogress",
        (file: DropzoneFile, progress: number, bytesSent: number): void => {
            emit("uploadProgress", { file, progress, bytesSent });
        }
    );

    // Bubble Total Upload Progress
    myDrop.on(
        "totaluploadprogress",
        (progress: number, totalBytes: number, bytesSent: number): void => {
            emit("totalUploadProgress", { progress, totalBytes, bytesSent });
        }
    );

    // Bubble any Errors that occur with Dropzone
    myDrop.on(
        "error",
        (
            file: DropzoneFile,
            message: laravelValidationErrors | string
        ): void => {
            if (typeof message === "string") {
                errMessage.value = message;
            } else {
                emit("error", { file, status: file.xhr?.status, message });
            }
        }
    );

    // When a file completes its upload, we will store the latest response
    myDrop.on("complete", (file: DropzoneFile): void => {
        completeMessage.value = file.xhr?.response;
    });

    // When all files have uploaded, we will emit success with the last complete message
    myDrop.on("queuecomplete", (): void => {
        emit("success", completeMessage.value);
    });
};

/*
 |-------------------------------------------------------------------------------
 | Validate the dropzone field by making sure there are no errors
 |-------------------------------------------------------------------------------
 */
const validate = (): boolean => {
    // If any files were rejected by Dropzone, we trigger an error
    let rejected = myDrop.getRejectedFiles();
    let queued = myDrop.getAcceptedFiles();

    hasFile.value = queued.length > 0 ? true : false;

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
    errMessage.value = null;
    overMaxFiles.value = false;
    return true;
};

/*
 |-------------------------------------------------------------------------------
 | Process and Upload all files along with Form Data
 |-------------------------------------------------------------------------------
 */
const process = (form: { [key: string]: any }): void => {
    fileFormData = form;

    // If no files are present, we do an axios submission
    if (myDrop.files.length === 0) {
        dataPost(props.uploadUrl.toString(), fileFormData)
            .catch((err) => {
                emit("error", err);
            })
            .then((res) => {
                emit("success", res?.data ?? "success");
            });
    } else {
        myDrop.processQueue();
    }
};

/*
 |-------------------------------------------------------------------------------
 | Reset Dropzone to its initial state
 |-------------------------------------------------------------------------------
 */
const reset = (): void => {
    myDrop.removeAllFiles();

    nextTick((): void => {
        errMessage.value = null;
        isDirty.value = false;
        myDrop.options.autoProcessQueue = false;
    });
};

/*
 |-------------------------------------------------------------------------------
 | Cancel the upload process and remove all files from queue
 |-------------------------------------------------------------------------------
 */
const cancelUpload = (): void => {
    const fileList = myDrop.files;
    fileList.forEach((file) => myDrop.cancelUpload(file));
};

defineExpose({
    process,
    reset,
    cancelUpload,
    validate,
    isDirty: isDirty,
    hasFile: hasFile,
});
</script>

<template>
    <div>
        <div class="dropzone pb-4" id="dropzone-container">
            <div class="dz-message">
                <fa-icon icon="cloud-arrow-up" />
                {{ uploadMessage ?? "Drag file here or click to upload" }}
            </div>
        </div>
        <div id="preview-template-container">
            <div class="dz-preview-wrapper">
                <div class="dz-preview">
                    <div class="dz-preview dz-file-preview">
                        <div class="dz-image">
                            <img data-dz-thumbnail />
                        </div>
                        <div class="dz-details">
                            <div class="dz-size" data-dz-size></div>
                            <div class="dz-filename">
                                <span data-dz-name />
                            </div>
                        </div>
                        <span class="dz-remove dz-tooltip" data-dz-remove>
                            <fa-icon icon="fa-trash-alt" />
                            <span class="dz-tooltip-text">Remove File</span>
                        </span>
                        <div class="dz-progress">
                            <span class="dz-upload" data-dz-uploadprogress />
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
        <div v-if="errMessage" class="dz-validation-message">
            {{ errMessage }}
        </div>
    </div>
</template>
