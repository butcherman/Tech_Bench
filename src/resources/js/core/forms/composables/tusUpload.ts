import * as tus from "tus-js-client";
import { onBeforeUnmount, readonly, ref } from "vue";

export const useTusUpload = () => {
    let upload: tus.Upload | null = null;

    const uploading = ref(false);
    const progress = ref(0);
    const error = ref<string | null>(null);
    const fileQueue = ref<QueuedFile[]>([]);

    const addFile = (file: File) => {
        fileQueue.value.push({
            file,
            status: "idle",
        });
    };

    const removeFile = (file: File) => {
        let fileIndex = fileQueue.value.findIndex((f) => f.file === file);

        if (fileIndex >= 0) {
            fileQueue.value.splice(fileIndex, 1);
        }
    };

    const startUpload = (): void => {
        upload?.abort();

        uploading.value = true;

        fileQueue.value.forEach((queuedFile) => {
            upload = new tus.Upload(queuedFile.file, {
                endpoint: "/tus",
                chunkSize: 5_000_000,

                metadata: {
                    name: queuedFile.file.name,
                    type: queuedFile.file.type,
                    purpose: "logo",
                },

                onError(uploadError) {
                    uploading.value = false;
                    error.value = uploadError.message;
                },

                onProgress(bytesUploaded, bytesTotal) {
                    progress.value = Math.round(
                        (bytesUploaded / bytesTotal) * 100,
                    );
                },

                onSuccess() {
                    uploading.value = false;
                    progress.value = 100;

                    console.log("success");
                },
            });

            upload.start();
        });
    };

    const cancelUpload = (): void => {
        upload?.abort();

        upload = null;
        uploading.value = false;
        progress.value = 0;
    };

    const resetStats = (): void => {
        error.value = null;
        progress.value = 0;
    };

    onBeforeUnmount(() => {
        upload?.abort();
    });

    return {
        uploading: readonly(uploading),
        progress: readonly(progress),
        error: readonly(error),
        fileQueue: readonly(fileQueue),
        addFile,
        cancelUpload,
        startUpload,
        removeFile,
        resetStats,
    };
};
