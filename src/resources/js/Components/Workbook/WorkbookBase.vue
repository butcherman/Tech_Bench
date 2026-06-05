<script setup lang="ts">
import Card from "../_Base/Card.vue";

defineProps<{
    workbookData: workbookWrapper;
}>();
</script>

<template>
    <Card> {{ workbookData }} </Card>
</template>
