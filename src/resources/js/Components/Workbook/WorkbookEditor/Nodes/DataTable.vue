<script setup lang="ts">
import { computed } from "vue";
import NodeOptions from "../NodeOptions.vue";

const props = defineProps<{
    allowAddRow: boolean;
    allowDeleteRow: boolean;
    allowExport: boolean;
    allowImport: boolean;
    columns: workbookTableColumn[];
    defaultRows: number;
    hideBorders: boolean;
    index: string;
    numberRows: boolean;
}>();

const borderClass = computed(() => !props.hideBorders);

/**
 * Determine what type of input should be displayed
 */
const getInputType = (column: workbookTableColumn): string => {
    switch (column.type) {
        case "Number":
            return "number";
        case "Checkbox":
            return "checkbox";
        default:
            return "text";
    }
};
</script>

<template>
    <div class="relative group/table">
        <NodeOptions
            class="hidden group-hover/table:flex"
            :can-edit="true"
            :node-index="index"
        />
        <table
            class="table-auto w-full border-slate-200"
            :class="{ border: borderClass }"
        >
            <thead>
                <tr class="border-slate-200" :class="{ border: borderClass }">
                    <td
                        v-if="numberRows"
                        class="border-slate-200"
                        :class="{ border: borderClass }"
                    ></td>
                    <th
                        v-for="col in columns"
                        class="border-slate-200"
                        :class="{ border: borderClass }"
                    >
                        {{ col.name }}
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="row in defaultRows"
                    class="border-slate-200"
                    :class="{ border: borderClass }"
                >
                    <td
                        v-if="numberRows"
                        class="border-slate-200 text-center"
                        :class="{ border: borderClass }"
                    >
                        {{ row }}
                    </td>
                    <td
                        v-for="col in columns"
                        class="border-slate-200 px-1"
                        :class="{ border: borderClass }"
                    >
                        <select
                            v-if="col.type === 'Drop List'"
                            class="w-full m-0 py-0 px-2"
                        >
                            <option />
                            <option v-for="opt in col.list.split(',')">
                                {{ opt }}
                            </option>
                        </select>
                        <input
                            v-else
                            :type="getInputType(col)"
                            class="w-full m-0 px-2"
                        />
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
