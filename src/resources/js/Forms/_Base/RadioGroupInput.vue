<script setup lang="ts">
import { RadioButton, Message } from "primevue";
import { computed, ref, toRef } from "vue";
import { useField } from "vee-validate";

interface radioGroupList {
    label: string;
    value: string;
}

defineEmits(["change"]);

const props = defineProps<{
    id: string;
    list: string[] | radioGroupList[];
    name: string;
    disabled?: boolean;
    help?: string;
    inline?: boolean;
}>();

const hasFocus = ref(false);
const flexDirection = computed(() => (props.inline ? "flex-row" : "flex-col"));

const nameRef = toRef(props, "name");
const { errorMessage, value } = useField(nameRef);
</script>

<template>
    <div class="flex gap-1 flex-wrap" :class="flexDirection">
        <div v-for="(item, index) in list">
            <RadioButton
                v-model="value"
                size="small"
                :id="`radio-group-${name}-${index}`"
                :name="name"
                :value="typeof item === 'string' ? item : item.value"
                :pt="{
                    box: () => ({
                        class: ['border border-slate-400'],
                    }),
                }"
                class="mb-1"
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
