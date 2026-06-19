<script setup lang="ts">
const props = defineProps<{
    validatedResults: workbookValidationData[];
}>();
</script>

<template>
    <table class="table-auto w-full">
        <thead>
            <tr>
                <th
                    v-for="(col, index) in validatedResults[0]"
                    :key="index"
                    class="border border-slate-300"
                    :class="{
                        'bg-red-300': col.validation_error === 'Invalid Column',
                    }"
                >
                    {{ index }}
                    <div v-if="!col.valid" class="text-sm text-danger">
                        {{ col.validation_error }}
                    </div>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(row, index) in validatedResults" :key="index">
                <td
                    v-for="(col, name) in row"
                    :key="name"
                    :class="{ 'bg-red-300': !col.valid }"
                    class="border border-slate-300 px-2"
                >
                    {{ col.value }}
                    <div v-if="!col.valid" class="text-danger text-sm">
                        {{ col.validation_error }}
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</template>
