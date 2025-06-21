<script setup lang="ts">
import { RadioButton, Message } from "primevue";
import { computed, ref, toRef } from "vue";
import { useField } from "vee-validate";
import type { Ref } from "vue";

interface radioGroupList {
    label: string;
    value: string;
}

defineEmits<{
    change: [string | boolean];
}>();

const props = defineProps<{
    id: string;
    list: string[] | radioGroupList[];
    name: string;
    disabled?: boolean;
    help?: string;
    inline?: boolean;
}>();

const hasFocus = ref<boolean>(false);
const flexDirection = computed<string>(() =>
    props.inline ? "flex-row" : "flex-col"
);

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
    value: Ref<string | boolean>;
} = useField(nameRef);
</script>

<template>
    <div class="flex gap-1 flex-wrap my-2" :class="flexDirection">
        <div v-for="(item, index) in list">
            <RadioButton
                v-model="value"
                size="small"
                class="mb-1"
                :input-id="`radio-group-${name}-${index}`"
                :name="name"
                :value="typeof item === 'string' ? item : item.value"
                :pt="{
                    box: () => ({
                        class: ['border border-slate-400'],
                    }),
                }"
                @focus="hasFocus = true"
                @blur="hasFocus = false"
                @change="$emit('change', value)"
            />
            <label :for="`radio-group-${name}-${index}`" class="ms-1">
                <slot name="item-label" :item="item">
                    {{ typeof item === "string" ? item : item.label }}
                </slot>
            </label>
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
