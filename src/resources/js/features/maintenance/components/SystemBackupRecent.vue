<script setup lang="ts">
import Card from "@/core/components/Card.vue";

const props = defineProps<{
    backups: BackupInfo[];
}>();
</script>

<template>
    <Card title="Recent Backups">
        <pre>{{ backups }}</pre>
    </Card>
</template>
