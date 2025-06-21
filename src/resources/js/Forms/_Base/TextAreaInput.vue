<script setup lang="ts">
import Textarea from "primevue/textarea";
import { FloatLabel, Message } from "primevue";
import { toRef, ref, computed } from "vue";
import { useField } from "vee-validate";
import type { Ref } from "vue";

const emit = defineEmits<{
    blur: [];
    change: [];
    focus: [];
}>();

const props = defineProps<{
    id: string;
    name: string;
    disabled?: boolean;
    help?: string;
    label?: string;
    placeholder?: string;
    rows?: number;
}>();

/**
 * Auto Hide the placeholder when not in focus to support Floating Label.
 */
const inputPlaceholder = computed<string>(() =>
    props.placeholder && (hasFocus.value || !props.label)
        ? props.placeholder
        : ""
);

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
const nameRef = toRef(props, "name");
const {
    errorMessage,
    value,
}: {
    errorMessage: Ref<string | undefined, string | undefined>;
    value: Ref<string>;
} = useField(nameRef);
</script>

<template>
    <div class="my-2">
        <FloatLabel variant="on">
            <Textarea
                v-model="value"
                class="w-full border p-2"
                :disabled="disabled"
                :id="id"
                :placeholder="inputPlaceholder"
                :rows="rows ?? 3"
                @focus="onFocus"
                @blur="onBlur"
            />
            <label for="on_label">{{ label }}</label>
        </FloatLabel>
        <Message size="small" severity="error" variant="simple">
            {{ errorMessage }}
        </Message>
        <Message
            v-if="hasFocus"
            severity="secondary"
            size="small"
            variant="simple"
        >
            {{ help }}
        </Message>
    </div>
</template>
