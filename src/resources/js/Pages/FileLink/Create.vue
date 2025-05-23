<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import FileLinkCreateForm from "@/Forms/FileLink/FileLinkCreateForm.vue";

defineProps<{
    defaultExpire: string;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div class="flex justify-center">
        <Card class="tb-card" title="New File Link">
            <FileLinkCreateForm :default-expire="defaultExpire" />
        </Card>
    </div>
</template>
