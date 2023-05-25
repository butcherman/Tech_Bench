<template>
    <div class="mb-3">
        <label v-if="label" :for="id" class="form-label w-100">
            {{ label }}
        </label>
        <Editor
            api-key="no-api-key"
            v-model="value"
            :id="id"
            class="form-control"
            :class="{ 'is-valid': isValid, 'is-invalid': isInvalid }"
            :disabled="disabled"
            :init="editorInit"
            @change="$emit('change', value)"
        />
        <span
            v-if="errorMessage && (meta.dirty || meta.touched)"
            class="text-danger"
            >{{ errorMessage }}</span
        >
    </div>
</template>

<script setup lang="ts">
import Editor from "@tinymce/tinymce-vue";
import { toRef, computed } from "vue";
import { useField } from "vee-validate";

defineEmits(["change"]);

const props = defineProps<{
    id: string;
    name: string;
    label?: string;
    disabled?: boolean;
}>();

const editorInit = {
    plugins: "autolink advlist lists link image table fullscreen preview code",
    height: 500,
    browser_spellcheck: true,
    toolbar:
        "undo redo | blocks | bold italic strikethrough forecolor | alignleft aligncenter alignright alignjustify | numlist bullist outdent indent | removeformat | table | fullscreen preview link image code",
    relative_urls: false,
    automatic_uploads: true,
    images_upload_url: route("upload-image"),
    file_picker_types: "image",
    image_dimensions: false,
    image_class_list: [
        {
            title: "Responsive",
            value: "img-fluid",
        },
    ],
    //  TODO - update and type this
    file_picker_callback: function (cb) {
        var input = document.createElement("input");
        input.setAttribute("type", "file");
        input.setAttribute("accept", "image/*");
        input.onchange = function () {
            var file = this.files[0];
            var reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = function () {
                var id = "blobid" + new Date().getTime();
                var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                var base64 = reader.result.split(",")[1];
                var blobInfo = blobCache.create(id, file, base64);
                blobCache.add(blobInfo);
                cb(blobInfo.blobUri(), { title: file.name });
            };
        };
        input.click();
    },
};

const isValid = computed<boolean>(() => {
    return meta.valid && meta.validated && !meta.pending;
});

const isInvalid = computed<boolean>(() => {
    return !meta.valid && meta.validated && !meta.pending;
});

const nameRef = toRef(props, "name");
const { errorMessage, value, meta } = useField(nameRef);
</script>

<style>
.tox-notifications-container {
    display: none;
}
</style>
