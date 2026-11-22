<script setup lang="ts" generic="TData">
import { computed } from "vue";

const props = defineProps<{
    data: TData;

    // Optional
    bordered?: boolean;
    compact?: boolean;
    only?: string[];
    skip?: string[];
}>();

const spacingClass = computed(() => (props.compact ? "p-0 px-2" : "p-2"));

/**
 * Translate the table header from snake_case to Title Case
 */
const toTitleCase = (str: string): string => {
    return str
        .split("_")
        .map(function (item) {
            return item.charAt(0).toUpperCase() + item.substring(1);
        })
        .join(" ");
};
</script>

<template>
    <table class="table-fixed border-collapse" :class="{ border: bordered }">
        <tbody>
            <template v-for="(value, index) in data" :key="index">
                <tr
                    v-if="
                        only?.includes(index.toString()) ||
                        !skip?.includes(index.toString())
                    "
                    class="border-b"
                >
                    <slot
                        name="row"
                        :row-data="{
                            value,
                            index,
                            toTitle: toTitleCase(index.toString()),
                        }"
                    >
                        <th class="max-w-1/2 w-1/3" :class="spacingClass">
                            <slot
                                name="index"
                                :row-data="{
                                    value,
                                    index,
                                    toTitle: toTitleCase(index.toString()),
                                }"
                            >
                                <div class="text-end">
                                    {{ toTitleCase(index.toString()) }}:
                                </div>
                            </slot>
                        </th>
                        <td :class="spacingClass">
                            <slot name="value" :row-data="{ value, index }">
                                <span v-if="typeof value === 'boolean'">
                                    <fa-icon
                                        :icon="value ? 'check' : 'xmark'"
                                        :class="
                                            value
                                                ? 'text-success'
                                                : 'text-danger'
                                        "
                                    />
                                </span>
                                <span v-else>
                                    {{ value }}
                                </span>
                            </slot>
                        </td>
                    </slot>
                </tr>
            </template>
        </tbody>
    </table>
</template>
