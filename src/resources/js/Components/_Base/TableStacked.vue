<template>
    <table class="table-auto border-collapse border">
        <tbody>
            <tr v-for="(value, index) in rows" class="border">
                <slot name="row" :row-data="{ value, index }">
                    <th class="text-end p-2">
                        <slot name="index" :row-data="{ value, index }">
                            {{
                                titleCase
                                    ? toTitleCase(index.toString())
                                    : index
                            }}:
                        </slot>
                    </th>
                    <td class="p-2">
                        <slot name="value" :row-data="{ value, index }">
                            <span v-if="typeof value === 'boolean'">
                                <font-awesome-icon
                                    v-if="value"
                                    icon="check"
                                    class="text-success"
                                />
                                <font-awesome-icon
                                    v-else
                                    icon="xmark"
                                    class="text-danger"
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

<script setup lang="ts">
defineProps<{
    rows: any;
    titleCase?: boolean;
}>();

const toTitleCase = (str: string): string => {
    return str
        .split("_")
        .map(function (item) {
            return item.charAt(0).toUpperCase() + item.substring(1);
        })
        .join(" ");
};
</script>
