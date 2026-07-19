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

    help?: string;
    hideHelp?: boolean;
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
const hasError = computed(() => (errorMessage.value?.length ? true : false));
const hasFocus = ref<boolean>(false);
const onFocus = (): void => {
    hasFocus.value = true;
    emit("focus");
};
const onBlur = () => {
    hasFocus.value = false;
    emit("blur");
};
const showHelp = computed(() => {
    if (!props.hideHelp) {
        return true;
    }

    return hasFocus.value;
});

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
            <!-- <div>append front</div> -->
            <div class="grow">
                <div class="relative form-input-base" :class="styleClass">
                    <input
                        v-model="value"
                        class="block peer"
                        type="text"
                        :autocomplete="name"
                        :class="{
                            invalid: hasError,
                        }"
                        :id="id"
                        :placeholder="placeholder"
                        :name="name"
                        @focus="onFocus"
                        @blur="onBlur"
                    />
                    <label :for="id">
                        {{ label }}
                    </label>
                </div>
            </div>
            <!-- <div>append end</div> -->
        </div>
        <div class="text-xs text-danger">{{ errorMessage }}</div>
        <div v-if="showHelp" class="text-sm text-muted">{{ help }}</div>
    </div>
</template>
