<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import FileLinkCreateForm from "@/Forms/FileLink/FileLinkCreateForm.vue";

defineProps<{
    defaultExpire: string;
    linkHash: string;
    allowCustomUrl: boolean;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div class="flex justify-center">
        <Card class="tb-card" title="New File Link">
            <FileLinkCreateForm
                :default-expire="defaultExpire"
                :link-hash="linkHash"
                :allow-custom-url="allowCustomUrl"
            />
        </Card>
    </div>
</template>
