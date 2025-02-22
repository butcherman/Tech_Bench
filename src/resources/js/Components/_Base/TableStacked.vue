<script setup lang="ts" generic="T">
defineProps<{
    bordered?: boolean;
    items: T;
}>();

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
    <table class="table-auto border-collapse" :class="{ border: bordered }">
        <tbody>
            <tr v-for="(value, index) in items" class="border-b">
                <slot name="row" :row-data="{ value, index }">
                    <th class="text-end p-2">
                        <slot name="index" :row-data="{ value, index }">
                            {{ toTitleCase(index.toString()) }}:
                        </slot>
                    </th>
                    <td class="p-2">
                        <slot name="value" :row-data="{ value, index }">
                            <span v-if="typeof value === 'boolean'">
                                <fa-icon
                                    :icon="value ? 'check' : 'xmark'"
                                    :class="
                                        value ? 'text-success' : 'text-danger'
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
        </tbody>
    </table>
</template>
