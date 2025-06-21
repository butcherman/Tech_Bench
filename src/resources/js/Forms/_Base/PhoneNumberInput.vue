<script setup lang="ts">
import {
    FloatLabel,
    InputGroup,
    InputGroupAddon,
    InputText,
    Message,
} from "primevue";
import { computed, ref, toRef, watch } from "vue";
import { useField } from "vee-validate";
import type { Ref } from "vue";

const emit = defineEmits<{
    focus: [];
    blur: [];
}>();

const props = defineProps<{
    id: string;
    name: string;
    autocomplete?: string;
    borderless?: boolean;
    disabled?: boolean;
    focus?: boolean;
    help?: string;
    label?: string;
    placeholder?: string;
}>();

/**
 * Auto Hide the placeholder when not in focus to support Floating Label.
 */
const inputPlaceholder = computed<string>(() => {
    if (props.placeholder && (hasFocus.value || !props.label)) {
        return props.placeholder;
    }

    if (hasFocus.value || !props.label) {
        return "+1 (XXX) XXX-XXXX";
    }

    return "";
});

/**
 * When a valid phone number in inserted, format to a readable number
 * yet keep the value as only the 10 digit number
 */
const formattedValue = ref<string | null>(null);
watch(formattedValue, (newVal: string | null) => {
    let cleaned = `${newVal}`.replace(/\D/g, "");
    let parts = cleaned.match(/^(1|)?([2-9]{1}\d{2})(\d{3})(\d{4})$/);

    if (parts) {
        formattedValue.value = `+1 (${parts[2]}) ${parts[3]}-${parts[4]}`;
    }

    setValue(cleaned.replace(/^1/, ""));
});

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
| Vee-Validate
|-------------------------------------------------------------------------------
*/
const nameRef = toRef(props, "name");
const {
    errorMessage,
    setValue,
}: {
    errorMessage: Ref<string | undefined, string | undefined>;
    setValue: (val: string) => void;
} = useField(nameRef);
</script>

<template>
    <div class="my-2">
        <InputGroup :class="{ 'border-b': borderless }">
            <InputGroupAddon
                v-if="$slots['start-text']"
                :class="{ 'border-hidden!': borderless }"
            >
                <slot name="start-text" />
            </InputGroupAddon>
            <FloatLabel variant="on">
                <InputText
                    v-model="formattedValue"
                    :autocomplete="autocomplete ?? 'off'"
                    :autofocus="focus"
                    :disabled="disabled"
                    :class="{
                        'border-hidden!': borderless,
                    }"
                    :input-id="id"
                    :invalid="errorMessage ? true : false"
                    :placeholder="inputPlaceholder"
                    type="tel"
                    @focus="onFocus"
                    @blur="onBlur"
                />
                <label :for="id">{{ label }}</label>
            </FloatLabel>
            <InputGroupAddon
                v-if="$slots['end-text']"
                :class="{ 'border-hidden!': borderless }"
            >
                <slot name="end-text" />
            </InputGroupAddon>
        </InputGroup>
        <Message size="small" severity="error" variant="simple">
            <span v-html="errorMessage" />
        </Message>
        <Message
            v-show="hasFocus"
            size="small"
            severity="secondary"
            variant="simple"
        >
            {{ help }}
        </Message>
    </div>
</template>
