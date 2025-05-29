<script setup lang="ts">
import { Password, FloatLabel, Message } from "primevue";
import { computed, ref, toRef } from "vue";
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
    type?: string;
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
        <FloatLabel variant="on">
            <Password
                v-model="value"
                :autocomplete="autocomplete ?? 'off'"
                :autofocus="focus"
                :class="{ 'border-b': borderless }"
                :disabled="disabled"
                :feedback="false"
                :input-id="id"
                :inputClass="{
                    'border-hidden!': borderless,
                }"
                :placeholder="inputPlaceholder"
                pt:maskIcon:class="pointer"
                pt:unmaskIcon:class="pointer"
                fluid
                toggle-mask
                @focus="onFocus"
                @blur="onBlur"
            />
            <label :for="id">{{ label }}</label>
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
        </FloatLabel>
    </div>
</template>

<style lang="postcss">
@reference '../../../css/app.css';

.p-password-input {
    @apply shadow-none;
}
</style>
