<template>
    <div>
        <InputGroup>
            <InputGroupAddon
                v-if="$slots['start-text']"
                class="border border-e-0 my-2"
            >
                <slot name="start-text" />
            </InputGroupAddon>
            <FloatLabel variant="on" class="my-2">
                <InputText
                    v-model="formattedValue"
                    class="p-2"
                    type="tel"
                    :autocomplete="autocomplete ?? 'off'"
                    :autofocus="focus"
                    :class="borderType"
                    :disabled="disabled"
                    :id="id"
                    :placeholder="
                        placeholder ? placeholder : '+1 (XXX) XXX-XXXX'
                    "
                    :variant="filled ? 'filled' : null"
                    fluid
                    @focus="hasFocus = true"
                    @blur="hasFocus = false"
                />
                <label :for="id">{{ label }}</label>
            </FloatLabel>
            <InputGroupAddon
                v-if="$slots['end-text']"
                class="border border-s-0 my-2"
            >
                <slot name="end-text" />
            </InputGroupAddon>
        </InputGroup>
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

<script setup lang="ts">
import {
    FloatLabel,
    InputText,
    Message,
    InputGroup,
    InputGroupAddon,
} from "primevue";
import { ref, toRef, computed, watch, onMounted } from "vue";
import { useField } from "vee-validate";

defineEmits(["change"]);

const props = defineProps<{
    id: string;
    name: string;
    autocomplete?: string;
    borderBottom?: boolean;
    disabled?: boolean;
    filled?: boolean;
    focus?: boolean;
    help?: string;
    label?: string;
    placeholder?: string;
}>();

const hasFocus = ref(false);
const formattedValue = ref<string | null>(null);

const borderType = computed(() =>
    props.borderBottom ? "border-b rounded-none" : "border"
);

/**
 * When a valid phone number in inserted, format to a readable number
 * yet keep the value as only the 10 digit number
 */
watch(formattedValue, (newVal) => {
    let cleaned = `${newVal}`.replace(/\D/g, "");
    let parts = cleaned.match(/^(1|)?([2-9]{1}\d{2})(\d{3})(\d{4})$/);

    if (parts) {
        formattedValue.value = `+1 (${parts[2]}) ${parts[3]}-${parts[4]}`;
    }

    setValue(cleaned.replace(/^1/, ""));
});

const nameRef = toRef(props, "name");
const { errorMessage, value, setValue } = useField(nameRef);

onMounted(() => {
    if (typeof value.value === "string") {
        formattedValue.value = value.value;
    }
});
</script>

<style>
label {
    font-weight: bold;
}
</style>
