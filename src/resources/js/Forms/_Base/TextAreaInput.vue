<template>
    <FloatLabel variant="on" class="my-2">
        <Textarea
            v-model="value"
            class="w-full border p-2"
            :id="id"
            :rows="rows ?? 3"
            :placeholder="placeholder"
            :disabled="disabled"
            @focus="hasFocus = true"
            @blur="hasFocus = false"
        />
        <label for="on_label">{{ label }}</label>
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
</template>

<script setup lang="ts">
import Textarea from "primevue/textarea";
import { FloatLabel, Message } from "primevue";
import { toRef, ref } from "vue";
import { useField } from "vee-validate";
import type { Ref } from "vue";

defineEmits(["change"]);

const props = defineProps<{
    id: string;
    name: string;
    label?: string;
    placeholder?: string;
    disabled?: boolean;
    help?: string;
    rows?: number;
}>();

const hasFocus = ref(false);

const nameRef = toRef(props, "name");
const {
    errorMessage,
    value,
}: {
    errorMessage: Ref<string | undefined, string | undefined>;
    value: Ref<string>;
} = useField(nameRef);
</script>
