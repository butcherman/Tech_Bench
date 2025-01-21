<template>
    <div :class="{ 'justify-items-center': center }">
        <div>
            <ToggleSwitch
                :input-id="id"
                v-model="value"
                :disabled="disabled"
                @focus="hasFocus = true"
                @blur="hasFocus = false"
            />
            <label :for="id" class="align-top ms-2 text-muted">
                <slot name="label">
                    {{ label }}
                </slot>
            </label>
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
    </div>
</template>

<script setup lang="ts">
import { ToggleSwitch, Message } from "primevue";
import { toRef, ref } from "vue";
import { useField } from "vee-validate";
import type { Ref } from "vue";

const props = defineProps<{
    id: string;
    name: string;
    center?: boolean;
    disabled?: boolean;
    help?: string;
    inline?: boolean; // TODO - Add This Class
    label?: string;
}>();

const hasFocus = ref(false);

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
    value: Ref<string | boolean>;
} = useField(nameRef);
</script>
