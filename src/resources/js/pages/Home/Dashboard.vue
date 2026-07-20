<script setup lang="ts">
import BaseBadge from "@/core/components/badges/BaseBadge.vue";
import BaseButton from "@/core/components/buttons/BaseButton.vue";
import AppLayout from "@/layouts/AppLayout.vue";
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
<template>
    <div class="flex gap-2">
        <h2 class="pb-2">Dashboard</h2>
        <BaseBadge size="large" icon="triangle-exclamation" />
        <BaseButton text="clicky" href="/about" />
    </div>
</template>
