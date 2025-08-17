<script setup lang="ts">
import Card from "../_Base/Card.vue";

const props = defineProps<{
    workbookData: workbookWrapper;
    activePage: string;
    values?: { [index: string]: string };
    workbookHash?: string;
}>();
</script>

<template>
    <Card>
        {{ workbookData }}
    </Card>
</template>
