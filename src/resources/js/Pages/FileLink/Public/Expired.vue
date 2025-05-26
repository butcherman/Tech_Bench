<script setup lang="ts">
import Card from "@/Components/_Base/Card.vue";
import LinkLayout from "@/Layouts/FileLink/LinkLayout.vue";
</script>

<script lang="ts">
export default { layout: LinkLayout };
</script>

<template>
    <div class="flex justify-center">
        <Card class="tb-card">
            <h4 class="text-center">Expired Link</h4>
            <p class="text-center">
                This link has expired. Please contact the person that sent you
                the link for instructions.
            </p>
        </Card>
    </div>
</template>
