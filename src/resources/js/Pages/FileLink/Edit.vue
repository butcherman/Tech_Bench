<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import FileLinkEditForm from "@/Forms/FileLink/FileLinkEditForm.vue";

defineProps<{
    link: fileLink;
    allowCustomUrl: boolean;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div class="flex justify-center">
        <Card class="tb-card-lg" title="Update File Link Details">
            <FileLinkEditForm :link="link" :allow-custom-url="allowCustomUrl" />
        </Card>
    </div>
</template>
