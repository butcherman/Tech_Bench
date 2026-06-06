<script setup lang="ts">
import Card from "../_Base/Card.vue";
import WorkbookBody from "./WorkbookBody.vue";
import WorkbookHeader from "./WorkbookHeader.vue";

defineProps<{
    workbookSkeleton: workbookWrapper;
}>();
</script>

<template>
    <Card class="h-full">
        <div class="flex flex-col h-full">
            <WorkbookHeader :header-skeleton="workbookSkeleton.header" />
            <form class="grow" novalidate v-focustrap @submit.prevent>
                <WorkbookBody :body-skeleton="workbookSkeleton.body" />
            </form>
            <WorkbookHeader :header-skeleton="workbookSkeleton.footer" />
        </div>
    </Card>
</template>
