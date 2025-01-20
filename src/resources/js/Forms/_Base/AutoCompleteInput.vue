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
                <AutoComplete
                    v-model="value"
                    class="p-2"
                    :type="type ?? 'text'"
                    :autofocus="focus"
                    :disabled="disabled"
                    :id="id"
                    :autocomplete="autocomplete ?? 'off'"
                    :class="borderType"
                    :variant="filled ? 'filled' : undefined"
                    :placeholder="placeholder"
                    :suggestions="searchResults"
                    :show-empty-message="false"
                    fluid
                    @focus="hasFocus = true"
                    @blur="hasFocus = false"
                    @complete="searchList"
                >
                    <template #option="{ option }">
                        {{ option }}
                    </template>
                </AutoComplete>
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
    AutoComplete,
    FloatLabel,
    Message,
    InputGroup,
    InputGroupAddon,
} from "primevue";
import { computed, ref, toRef } from "vue";
import { useField } from "vee-validate";
import type { Ref } from "vue";

const props = defineProps<{
    id: string;
    label?: string;
    name: string;
    autocomplete?: string;
    borderBottom?: boolean;
    disabled?: boolean;
    filled?: boolean;
    focus?: boolean;
    help?: string;
    type?: string;
    placeholder?: string;
    dataList: string[];
}>();

const hasFocus = ref<boolean>(false);
const searchResults = ref<string[]>([]);

/**
 * Filter the searched list based on what has been typed in the input box
 */
const searchList = (event: { query: string }) => {
    let query = event.query.trim().toLowerCase();

    if (!query.length) {
        searchResults.value = [...props.dataList];
        return;
    }

    searchResults.value = props.dataList.filter((item) => {
        return item.toLowerCase().startsWith(query);
    });
};

const borderType = computed(() =>
    props.borderBottom ? "border-b rounded-none" : "border"
);

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
    value: Ref<string>;
} = useField(nameRef);
</script>

<style>
.p-inputtext::placeholder {
    color: transparent;
}

.p-inputtext:focus::placeholder {
    /* color: inherit; */
    /* @apply text-muted; */
    @apply text-muted-color;
}
</style>
