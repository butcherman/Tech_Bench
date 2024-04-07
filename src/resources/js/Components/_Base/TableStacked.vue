<template>
    <div class="table-responsive">
        <table class="table">
            <tbody>
                <tr v-for="(value, index) in computedRows" :key="index">
                    <slot name="default" :row-data="{ value, index }">
                        <th class="text-end">
                            <slot name="index" :row-data="{ value, index }">
                                {{ index }}:
                            </slot>
                        </th>
                        <td>
                            <slot name="value" :row-data="{ value, index }">
                                {{ value }}
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
}>();

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
</script>
