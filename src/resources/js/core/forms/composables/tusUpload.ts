import * as tus from "tus-js-client";
import { onBeforeUnmount, readonly, ref } from "vue";

export const useTusUpload = () => {
    let upload: tus.Upload | null = null;

    const uploading = ref(false);
    const progress = ref(0);
    const error = ref<string | null>(null);
    const fileQueue = ref<File[]>([]);

    const addFile = (file: File) => {
        fileQueue.value.push(file);
    };

    const startUpload = (): void => {
        upload?.abort();

        uploading.value = true;

        fileQueue.value.forEach((file) => {
            upload = new tus.Upload(file, {
                endpoint: "/tus",
                chunkSize: 5_000_000,

                metadata: {
                    name: file.name,
                    type: file.type,
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
        resetStats,
    };
};
