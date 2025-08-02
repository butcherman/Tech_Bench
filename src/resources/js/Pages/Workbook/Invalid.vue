<script setup lang="ts">
import Card from "@/Components/_Base/Card.vue";
import PublicLayout from "@/Layouts/Public/PublicLayout.vue";
</script>

<script lang="ts">
export default { layout: PublicLayout };
</script>

<template>
    <div class="flex justify-center">
        <Card class="tb-card">
            <h4 class="text-center">This Workbook is no longer available</h4>
            <p class="text-center">
                The Workbook has either expired or has been removed
            </p>
        </Card>
    </div>
</template>
