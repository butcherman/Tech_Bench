import { computed, readonly, ref } from "vue";

export const useUploadHelper = (props: InputFileProps) => {
    /*
    |---------------------------------------------------------------------------
    | File Queue
    |---------------------------------------------------------------------------
    */
    const fileQueue = ref<InputQueuedFile[]>([]);
    const rejectedFiles = ref<InputRejectedFile[]>([]);

    /**
     * Cycle through list of files and add to queue
     */
    const processFileList = (fileList: FileList | File[]): void => {
        for (let i = 0; i < fileList.length; i++) {
            let file = fileList[i];
            if (file) {
                addFileToQueue(file);
            }
        }
    };

    /**
     * Add file to the file queue
     */
    const addFileToQueue = (file: File): void => {
        let { error, allowRetry } = validateFile(file);

        if (error) {
            rejectFile(file, error, allowRetry);
            return;
        }

        fileQueue.value.push({
            file,
            status: "pending",
            progress: 0,
        });
    };

    /**
     * Add file to the rejected queue
     */
    const rejectFile = (
        file: File,
        error: string,
        allowRetry: boolean,
    ): void => {
        rejectedFiles.value.push({
            file,
            error,
            allowRetry,
        });
    };

    /**
     * Validate a file
     */
    const validateFile = (
        file: File,
    ): { error: string | null; allowRetry: boolean } => {
        // Verify file does not trigger too many files error
        if (fileQueue.value.length >= (props.maxFiles ?? 1)) {
            return {
                error: `Too many files.  ${props.maxFiles ?? 1} allowed.`,
                allowRetry: true,
            };
        }

        // Verify the file type is allowed
        if (props.acceptedFiles) {
            let mime = file.type;
            if (!props.acceptedFiles.includes(mime)) {
                return {
                    error: "This file type is not allowed.",
                    allowRetry: false,
                };
            }
        }

        // Verify that the file is not too big
        if (file.size > 10_000_000_000) {
            return { error: "File is too large to upload", allowRetry: false };
        }

        return { error: null, allowRetry: true };
    };

    /*
    |---------------------------------------------------------------------------
    | Drag Events
    |---------------------------------------------------------------------------
    */
    const dragging = ref<boolean>(false);

    /**
     * Drag has entered the dropzone
     */
    const onDragEnter = (): void => {
        dragging.value = true;
    };

    /**
     * Drag has left the dropzone
     */
    const onDragLeave = (): void => {
        dragging.value = false;
    };

    /*
    |---------------------------------------------------------------------------
    | Manual File Selection
    |---------------------------------------------------------------------------
    */

    /**
     * Remove a file from the queue
     */
    const onRemoveFile = (file: File): void => {
        // Search primary queue
        let fileIndex = fileQueue.value.findIndex((f) => f.file === file);
        if (fileIndex >= 0) {
            fileQueue.value.splice(fileIndex, 1);
            retryRejectedFiles();
            return;
        }

        // Search rejected queue
        let rejectedIndex = rejectedFiles.value.findIndex(
            (f) => f.file === file,
        );
        if (rejectedIndex >= 0) {
            rejectedFiles.value.splice(rejectedIndex, 1);
            retryRejectedFiles();
            return;
        }
    };

    /**
     * See if any of the rejected files can be brought into the main queue
     */
    const retryRejectedFiles = (): void => {
        if (!rejectedFiles.value.length) {
            return;
        }

        rejectedFiles.value.forEach((rejected, index) => {
            if (rejected.allowRetry) {
                let { error } = validateFile(rejected.file);

                if (error === null) {
                    fileQueue.value.push({
                        file: rejected.file,
                        status: "pending",
                        progress: 0,
                    });

                    rejectedFiles.value.splice(index, 1);
                }
            }
        });
    };

    /*
    |---------------------------------------------------------------------------
    | File upload progress
    |---------------------------------------------------------------------------
    */
    const totalProgress = computed(() => {
        const totalBytes = fileQueue.value.reduce(
            (total, queuedFile) => total + queuedFile.file.size,
            0,
        );

        if (totalBytes === 0) {
            return 0;
        }

        const uploadedBytes = fileQueue.value.reduce(
            (total, queuedFile) =>
                total + queuedFile.file.size * (queuedFile.progress / 100),
            0,
        );

        return Math.round((uploadedBytes / totalBytes) * 100);
    });

    return {
        dragging: readonly(dragging),
        fileQueue,
        rejectedFiles,
        totalProgress,

        onDragEnter,
        onDragLeave,
        onRemoveFile,
        processFileList,
    };
};
