<template>
    <tbody>
        <tr v-for="i in searchParams.perPage" :key="i">
            <td class="placeholder-glow">
                <span
                    class="placeholder"
                    :class="`col-${Math.floor(Math.random() * 10 - 3 + 1) + 3}`"
                />
            </td>
            <td class="placeholder-glow">
                <span
                    class="placeholder"
                    :class="`col-${Math.floor(Math.random() * 10 - 3 + 1) + 3}`"
                />
            </td>
            <td class="placeholder-glow">
                <span
                    class="placeholder"
                    :class="`col-${Math.floor(Math.random() * 10 - 3 + 1) + 3}`"
                />
            </td>
        </tr>
    </tbody>
</template>

<script setup lang="ts">
import { searchParams } from "@/Modules/CustomerSearch.module";
</script>
