<script setup lang="ts">
import { useField } from "vee-validate";
import { computed, Ref, ref, toRef } from "vue";

const emit = defineEmits<{
    focus: [];
    blur: [];
    change: [];
}>();

const props = defineProps<{
    id: string;
    name: string;
    // Optional
    help?: string;
    hideHelp?: boolean;
    hideUnmask?: boolean;
    inputStyle?: "filled" | "standard" | "outlined";
    label?: string;
    placeholder?: string;
}>();

/**
 * Variant styling for the input
 */
const styleClass = computed<string>(() => {
    switch (props.inputStyle) {
        case "standard":
            return "form-input-standard";
        case "filled":
            return "form-input-filled";
        default:
            return "form-input-outlined";
    }
});

/*
|-------------------------------------------------------------------------------
| Input State
|-------------------------------------------------------------------------------
*/
const hasError = computed<boolean>(() =>
    errorMessage.value?.length ? true : false,
);

const hasFocus = ref<boolean>(false);
const onFocus = (): void => {
    hasFocus.value = true;
    emit("focus");
};

const onBlur = (): void => {
    hasFocus.value = false;
    emit("blur");
};

const showHelp = computed<boolean>(() => {
    if (!props.hideHelp) {
        return true;
    }

    return hasFocus.value;
});

const showPass = ref<boolean>(false);
const maskType = computed<"text" | "password">(() =>
    showPass.value ? "text" : "password",
);
const maskIcon = computed<"eye-slash" | "eye">(() =>
    showPass.value ? "eye-slash" : "eye",
);

/*
|-------------------------------------------------------------------------------
| Vee-Validate
|-------------------------------------------------------------------------------
*/
const nameRef = toRef(props, "name");

const {
    errorMessage,
    value,
}: {
    errorMessage: Ref<string | undefined, string | undefined>;
    value: Ref<string | undefined>;
} = useField(nameRef);
</script>

<template>
    <div class="my-2">
        <div class="flex">
            <div class="grow">
                <div class="relative form-input-base" :class="styleClass">
                    <input
                        v-model="value"
                        :autocomplete="`current-${name}`"
                        class="block peer"
                        :type="maskType"
                        :class="{
                            invalid: hasError,
                        }"
                        :id="id"
                        :placeholder="placeholder ?? ''"
                        :name="name"
                        @focus="onFocus"
                        @blur="onBlur"
                    />
                    <div
                        v-if="!hideUnmask"
                        class="absolute inset-e-1.5 bottom-1.5 text-muted pointer"
                        @click="showPass = !showPass"
                    >
                        <fa-icon :icon="maskIcon" />
                    </div>
                    <label :for="id">
                        {{ label }}
                    </label>
                </div>
            </div>
        </div>
        <div class="text-xs text-danger">{{ errorMessage }}</div>
        <div v-if="showHelp" class="text-sm text-muted">{{ help }}</div>
    </div>
</template>
