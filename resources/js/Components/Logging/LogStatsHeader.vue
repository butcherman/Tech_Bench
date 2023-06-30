<template>
    <thead>
        <tr>
            <th v-if="hasFilename">Filename</th>
            <th>Total Entries</th>
            <template v-for="level in levels" :key="level.name">
                <th>
                    <fa-icon :icon="level.icon" :class="level.color" />
                    {{ level.name }}
                </th>
            </template>
        </tr>
    </thead>
</template>

<script setup lang="ts">
defineProps<{
    levels: logLevels[];
    hasFilename?: boolean;
}>();
</script>
