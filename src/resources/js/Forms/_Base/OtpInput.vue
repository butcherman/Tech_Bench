<script setup lang="ts">
import { InputOtp, Message } from "primevue";
import { useField } from "vee-validate";
import { toRef } from "vue";
import type { Ref } from "vue";

const props = defineProps<{
    id: string;
    name: string;
    disabled?: boolean;
    focus?: boolean;
    label?: string;
    length?: number;
}>();

/*
|---------------------------------------------------------------------------
| Vee-Validate
|---------------------------------------------------------------------------
*/
const nameRef = toRef(props, "name");
const {
    errorMessage,
    value,
}: {
    errorMessage: Ref<string | undefined, string | undefined>;
    value: Ref<"string" | boolean | undefined>;
} = useField(nameRef);
</script>

<template>
    <div class="my-2">
        <label v-if="label" :for="id" class="font-bold text-muted">
            {{ label }}
        </label>
        <InputOtp
            v-model="value"
            class="flex justify-center"
            size="large"
            :input-id="id"
            :length="length ?? 4"
        />
        <Message
            size="small"
            severity="error"
            variant="simple"
            class="flex justify-center"
        >
            <span v-html="errorMessage" />
        </Message>
    </div>
</template>
