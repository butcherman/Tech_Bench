<template>
    <FloatLabel variant="on" class="my-2">
        <Textarea
            v-model="value"
            class="w-full border p-2"
            :disabled="disabled"
            :id="id"
            :placeholder="inputPlaceholder"
            :rows="rows ?? 3"
            @focus="hasFocus = true"
            @blur="hasFocus = false"
        />
        <label for="on_label">{{ label }}</label>
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
    </FloatLabel>
</template>

<script setup lang="ts">
import Textarea from "primevue/textarea";
import { FloatLabel, Message } from "primevue";
import { toRef, ref, computed } from "vue";
import { useField } from "vee-validate";
import type { Ref } from "vue";

defineEmits(["change"]);

const props = defineProps<{
    id: string;
    name: string;
    disabled?: boolean;
    help?: string;
    label?: string;
    placeholder?: string;
    rows?: number;
}>();

const hasFocus = ref(false);
const inputPlaceholder = computed(() =>
    hasFocus.value ? props.placeholder : ""
);

const nameRef = toRef(props, "name");
const {
    errorMessage,
    value,
}: {
    errorMessage: Ref<string | undefined, string | undefined>;
    value: Ref<string>;
} = useField(nameRef);
</script>
