<script setup lang="ts">
import { Slider, Message } from "primevue";
import { ref, toRef } from "vue";
import { useField } from "vee-validate";
import type { Ref } from "vue";

const emit = defineEmits<{
    focus: [];
    blur: [];
}>();

const props = defineProps<{
    id: string;
    label: string;
    name: string;
    help?: string;
    min?: number;
    max?: number;
    valueText?: string;
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
| Vee-Validate
|-------------------------------------------------------------------------------
*/
const nameRef = toRef(props, "name");
const {
    errorMessage,
    value,
}: {
    errorMessage: Ref<string | undefined, string | undefined>;
    value: Ref<number>;
} = useField(nameRef);
</script>

<template>
    <div class="my-2">
        <label :for="id" class="text-muted font-bold mb-2">
            {{ label }}
        </label>
        <Slider
            v-model="value"
            class="my-2"
            :input-id="id"
            :name="name"
            :min="min"
            :max="max"
            @focus="onFocus"
            @blur="onBlur"
        />
        <div class="text-muted">
            <slot name="value-slot" :value="value">
                {{ valueText ?? "Value: " }}
                {{ value }}
            </slot>
        </div>
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
