<script setup lang="ts">
import AuthLayout from "@/layouts/AuthLayout.vue";
import Card from "@/core/components/Card.vue";

const props = defineProps<{}>();
</script>

<script lang="ts">
export default { layout: AuthLayout };
</script>
<template>
    <div class="h-screen flex flex-col justify-center items-center">
        <Card size="md" class="h-150 overflow-auto bg-black! text-white">
            Restore Backup
        </Card>
    </div>
</template>
