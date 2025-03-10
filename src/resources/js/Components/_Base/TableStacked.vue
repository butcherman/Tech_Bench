<template>
    <div class="table-responsive">
        <table class="table">
            <tbody>
                <tr v-for="(value, index) in computedRows" :key="index">
                    <slot name="default" :row-data="{ value, index }">
                        <th :class="[alignText, { 'w-50': limitWidth }]">
                            <slot name="index" :row-data="{ value, index }">
                                {{
                                    titleCase
                                        ? toTitleCase(index.toString())
                                        : index
                                }}:
                            </slot>
                        </th>
                        <td>
                            <slot name="value" :row-data="{ value, index }">
                                <span v-if="typeof value === 'boolean'">
                                    <fa-icon
                                        v-if="value"
                                        icon="check"
                                        class="text-success"
                                    />
                                    <fa-icon
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
    </div>
</template>

<script setup lang="ts">
import { computed } from "vue";

interface rowHeaders {
    label: string;
    field: string;
}

const props = defineProps<{
    rows: any;
    headers?: rowHeaders[];
    alignLeft?: boolean;
    titleCase?: boolean;
    limitWidth?: boolean;
}>();

const alignText = computed(() => (props.alignLeft ? "text-start" : "text-end"));

const computedRows = computed<{ [key: string]: string | any }>(() => {
    if (props.headers) {
        let rowData: { [key: string]: string } = {};

        props.headers.forEach(
            (header) => (rowData[header.label] = props.rows[header.field])
        );

        return rowData;
    }

    return props.rows;
});

const toTitleCase = (str: string): string => {
    return str
        .split("_")
        .map(function (item) {
            return item.charAt(0).toUpperCase() + item.substring(1);
        })
        .join(" ");
};
</script>
