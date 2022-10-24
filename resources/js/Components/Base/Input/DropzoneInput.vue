<template>
    <div id="dropzone-container">
        <div
            v-bind="getRootProps()"
            class="d-flex flex-column justify-content-center align-items-center"
        >
            <input v-bind="getInputProps()" />
            <p v-if="isDragActive">
                <fa-icon icon="fa-cloud-arrow-up" />
                Drop File Here...
            </p>
            <p v-else>
                <fa-icon icon="fa-cloud-arrow-up" />
                {{  label ? label : 'Drag file here or click to select files' }}
            </p>
            <p
                v-for="file in fileList"
                class="text-success text-center w-100"
            >
                {{ file.name }}
                <span
                    class="text-danger pointer"
                    title="Remove File"
                    @click.stop="removeFile(file)"
                >
                    <fa-icon icon="fa-xmark" />
                </span>
            </p>
            <p
                v-for="file in errorList"
                class="text-danger border border-danger p-2 w-100 text-center"
            >
                {{ file.file.name }} <br />
                <span v-for="f in file.errors">
                    {{ f?.message }}
                </span>
                <span class="text-danger pointer" title="Remove File" @click.stop="removeError(file)">
                    <fa-icon icon="fa-xmark" />
                </span>
            </p>
        </div>
    </div>
</template>

<script setup lang="ts">
    import { ref, toRef }            from 'vue';
    import { useDropzone }           from 'vue3-dropzone';
    import { useField }              from 'vee-validate';
    import type { FileRejectReason } from 'vue3-dropzone'

    const props = defineProps<{
        name      : string;
        label    ?: string;
        fileTypes?: string | string[];
        multiple ?: boolean,
        maxFiles ?: number,
    }>();

    const onDrop = (acceptFiles:fileListType[], rejectReasons:FileRejectReason[]):void => {
        console.log(acceptFiles);
        console.log(rejectReasons);

        addFiles(acceptFiles);
        addErrors(rejectReasons);
    }

    const { getRootProps, getInputProps, isDragActive } = useDropzone({
        onDrop,
        maxFiles: props.maxFiles ? props.maxFiles : 5,
        multiple: props.multiple ? props.multiple : false,
        accept  : props.fileTypes,
    });

    /**
     * Accepted file list
     */
    const fileList = ref<fileListType[]>([]);
    const addFiles = (newFiles:fileListType[]) => {
        newFiles.forEach((file:fileListType) => fileList.value.push(file));
        setValue(fileList.value);
    }
    //  Remove specific file from input
    const removeFile = (file:fileListType) => {
        let index = fileList.value.findIndex(f => f === file);
        fileList.value.splice(index, 1);
        setValue(fileList.value);
    }

    /**
     * Errored file list
     */
    const errorList = ref<FileRejectReason[]>([]);
    const addErrors = (newErr:FileRejectReason[]) => {
        newErr.forEach((err:FileRejectReason) => errorList.value.push(err));
    }
    //  Remove an error from the list
    const removeError = (err:FileRejectReason) => {
        let index = errorList.value.findIndex(e => e === err);
        errorList.value.splice(index, 1);
    }

    const nameRef      = toRef(props, 'name');
    const { setValue } = useField(nameRef);

    interface fileListType {
        path: string;
        name: string;
        size: number;
        type: string;
    }
</script>

<style scoped lang="scss">
    #dropzone-container {
        background-color: rgb(158, 219, 247, .25);
        border: 1px solid rgb(168, 164, 164, .75);
        border-radius: 15px;
        height: 75%;
        margin: 5px;
        padding: 5px;
        display: block;
        &:hover {
            background-color: rgba(57, 187, 247, 0.25);
        }
        div {
            height: 100%;
            overflow: auto;
        }
    }
</style>
