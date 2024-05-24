<template>
    <div class="mb-3">
        <label v-if="label" :for="id" class="form-label w-100">
            {{ label }}:
        </label>
        <div id="editor-toolbar" class="p-0 my-2"></div>
        <Editor
            v-model="value"
            :id="id"
            class="form-control editor-input"
            :class="{ 'is-valid': isValid, 'is-invalid': isInvalid }"
            :disabled="disabled"
            :init="editorInit"
            @change="$emit('change', value)"
        />
        <span
            v-if="errorMessage && (meta.dirty || meta.touched)"
            class="text-danger"
        >
            {{ errorMessage }}
        </span>
    </div>
</template>

<script setup lang="ts">
import "@/tinyMce";
import Editor from "@tinymce/tinymce-vue";
import { toRef, computed } from "vue";
import { useField } from "vee-validate";

defineEmits(["change"]);
const props = defineProps<{
    id: string;
    name: string;
    label?: string;
    disabled?: boolean;
    placeholder?: string;
    imageFolder?: string;
}>();

/**
 * Validation Data
 */
const isValid = computed<boolean>(() => {
    return meta.valid && meta.validated && !meta.pending;
});

const isInvalid = computed<boolean>(() => {
    return !meta.valid && meta.validated && !meta.pending;
});

const nameRef = toRef(props, "name");
const { errorMessage, value, meta } = useField(nameRef);

/**
 * TinyMCE Initialization
 */
const editorInit = {
    /**
     * Basic Settings
     */
    allow_script_urls: false,
    browser_spellcheck: true,
    custom_undo_redo_levels: 10,
    fixed_toolbar_container: "#editor-toolbar",
    height: 500,
    inline: true,
    placeholder: props.placeholder || null,
    relative_urls: false,
    resize_img_proportional: true,

    init_instance_callback: (editor: any) => editor.fire("focus"),
    setup: (editor: any) => {
        return editor.on("blur", () => false);
    },

    /**
     * Plugin List
     */
    plugins: [
        "advlist",
        "autolink",
        "code",
        "image",
        "link",
        "lists",
        "pagebreak",
        "preview",
        "table",
    ],

    /**
     * Plugin Settings
     */
    link_default_target: "_blank",
    formats: {
        table: { classes: "table" },
    },

    /**
     * Layout
     */
    toolbar:
        "undo redo bold italic strikethrough forecolor | alignleft aligncenter alignright alignjustify | numlist bullist outdent indent | removeformat | table image link preview code",
    extended_valid_elements:
        "table[align<center?left?right|bgcolor|cellpadding|cellspacing|class=table" +
        "|dir<ltr?rtl|frame|height|id|lang|onclick|ondblclick|onkeydown|onkeypress" +
        "|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|rules" +
        "|summary|title|width],",

    /**
     * Image Upload Settings
     */
    automatic_uploads: true,
    file_picker_types: "image",
    images_upload_url: route("upload-image", [props.imageFolder]),
    image_dimensions: false,
    image_class_list: [
        {
            title: "Responsive",
            value: "img-fluid",
        },
    ],
};
</script>

<style lang="scss">
.editor-input {
    min-height: 500px;
}

.tox-promotion {
    display: none;
}
</style>
