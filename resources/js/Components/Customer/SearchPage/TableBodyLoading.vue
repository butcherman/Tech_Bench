<template>
    <tbody>
        <tr v-for="i in searchParam.perPage" :key="i">
            <td class="placeholder-glow">
                <span
                    class="placeholder"
                    :class="`col-${Math.floor(
                        Math.random() * (10 - 3 + 1) + 3
                    )}`"
                />
            </td>
            <td class="placeholder-glow">
                <span
                    class="placeholder"
                    :class="`col-${Math.floor(
                        Math.random() * (10 - 3 + 1) + 3
                    )}`"
                />
            </td>
            <td class="placeholder-glow">
                <span
                    class="placeholder"
                    :class="`col-${Math.floor(
                        Math.random() * (10 - 3 + 1) + 3
                    )}`"
                />
            </td>
        </tr>
    </tbody>
</template>

<script setup lang="ts">
import { inject } from "vue";
import { customerSearchDataKey } from "@/SymbolKeys/CustomerKeys";

const { searchParam } = inject(
    customerSearchDataKey
) as customerSearchDataSymbol;
</script>

<style scoped lang="scss">
tbody {
    tr {
        td {
            padding: 0;
            a {
                display: block;
                height: 100%;
                padding: 8px 0;
                text-decoration: none;
                color: #000000;
            }
        }
    }
}
</style>
