import * as tus from "tus-js-client";
import { onBeforeUnmount, readonly, ref } from "vue";
import type { Ref } from "vue";

export const useTusUpload = (options: TusUploadOptions = {}) => {
    const { onFileUploaded, onQueueCompleted } = options;

    let upload: tus.Upload[] = [];

    const hasError = ref<boolean>(false);
    const completedList = ref<string[]>([]);

    const startUpload = (
        fileQueue: Ref<InputQueuedFile[]>,
        purpose: string,
    ): void => {
        fileQueue.value
            .filter((queuedFile) => queuedFile.status === "pending")
            .forEach((queuedFile) => {
                queuedFile.status = "uploading";

                let newUpload = new tus.Upload(queuedFile.file, {
                    endpoint: "/tus",
                    chunkSize: 5_000_000,

                    metadata: {
                        name: queuedFile.file.name,
                        type: queuedFile.file.type,
                        purpose,
                    },

                    onError(uploadError) {
                        queuedFile.error = uploadError.message;
                        queuedFile.status = "error";
                        hasError.value = true;
                    },

                    onProgress(bytesUploaded, bytesTotal) {
                        queuedFile.progress = Math.round(
                            (bytesUploaded / bytesTotal) * 100,
                        );
                    },

                    onSuccess() {
                        queuedFile.progress = 100;
                        queuedFile.status = "complete";

                        checkQueueCompleted(fileQueue);
                    },
                });

                upload.push(newUpload);

                newUpload.options.onSuccess = () => {
                    console.log(newUpload.url?.split("/").pop());
                    let fileId = newUpload.url?.split("/").pop();

                    if (fileId) {
                        onFileUploaded?.(fileId);
                        completedList.value.push(fileId);
                    }
                };

                newUpload.start();
            });
    };

    const checkQueueCompleted = (fileQueue: Ref<InputQueuedFile[]>): void => {
        const hasActiveUploads = fileQueue.value.some(
            (queuedFile) =>
                queuedFile.status === "pending" ||
                queuedFile.status === "uploading",
        );

        if (!hasActiveUploads) {
            onQueueCompleted?.(completedList.value);
        }
    };

    const cancelUpload = (): void => {
        if (upload) {
            upload.forEach((up) => up.abort());
        }

        upload = [];
    };

    const resetQueue = (): void => {
        cancelUpload();

        hasError.value = false;
        completedList.value = [];
    };

    onBeforeUnmount(() => {
        cancelUpload();
    });

    return {
        hasError: readonly(hasError),
        completedList: readonly(completedList),

        cancelUpload,
        startUpload,
        resetQueue,
    };
};
