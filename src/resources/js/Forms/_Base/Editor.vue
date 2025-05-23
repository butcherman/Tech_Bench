<script setup lang="ts">
import "@/tinyMce";
import Editor from "@tinymce/tinymce-vue";
import { toRef, computed, ref } from "vue";
import { useField } from "vee-validate";
import { Message } from "primevue";

const emit = defineEmits<{
    change: [unknown];
    focus: [];
    blur: [];
}>();

const props = defineProps<{
    id: string;
    name: string;
    label?: string;
    disabled?: boolean;
    placeholder?: string;
    imageFolder?: string;
    help?: string;
}>();

/*
|-------------------------------------------------------------------------------
| Input Focus State
|-------------------------------------------------------------------------------
*/
const hasFocus = ref<boolean>(false);

const onFocus = (): void => {
    hasFocus.value = true;
    emit("focus");
};

const onBlur = (): void => {
    hasFocus.value = false;
    emit("blur");
};

/*
|-------------------------------------------------------------------------------
| Vee Validate
|-------------------------------------------------------------------------------
*/
const isValid = computed<boolean>(() => {
    return meta.valid && meta.validated && !meta.pending;
});

const isInvalid = computed<boolean>(() => {
    return !meta.valid && meta.validated && !meta.pending;
});

const nameRef = toRef(props, "name");
const { errorMessage, value, meta } = useField(nameRef);

/*
|-------------------------------------------------------------------------------
| TinyMCE Initialization
|-------------------------------------------------------------------------------
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
    license_key: "gpl",

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

<template>
    <div class="mb-3">
        <label v-if="label" :for="id" class="w-full text-muted font-bold block">
            {{ label }}:
        </label>
        <div id="editor-toolbar" class="p-0 my-2"></div>
        <Editor
            v-model="value"
            :id="id"
            class="editor-input"
            :class="{ 'is-valid': isValid, 'is-invalid': isInvalid }"
            :disabled="disabled"
            :init="editorInit"
            license-key="gpl"
            @change="$emit('change', value)"
            @focus="onFocus"
            @blur="onBlur"
        />
        <Message size="small" severity="error" variant="simple">
            {{ errorMessage }}
        </Message>
        <Message
            v-if="hasFocus"
            size="small"
            severity="secondary"
            variant="simple"
        >
            {{ help }}
        </Message>
    </div>
</template>

<style lang="postcss">
.editor-input {
    min-height: 500px;
    @apply border border-slate-200 rounded-lg p-3;

    &:focus-visible {
        outline: none;
    }
}

.tox-promotion {
    display: none;
}
</style>
