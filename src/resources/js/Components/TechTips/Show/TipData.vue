<script setup lang="ts">
import ClipboardCopy from "@/Components/_Base/ClipboardCopy.vue";

defineProps<{
    techTip: techTip;
    isPublic?: boolean;
}>();
</script>

<template>
    <div class="text-muted">
        <div class="flex flex-col md:flex-row">
            <div class="px-1">
                <strong>ID:</strong>
                {{ techTip.tip_id }}
            </div>
            <div v-if="!isPublic" class="px-1 md:border-l border-l-slate-400">
                <strong>Tip Type:</strong>
                {{ techTip.type }}
            </div>
            <div class="px-1 md:border-l border-l-slate-400">
                <strong>Created:</strong>
                {{ techTip.created_at }}
            </div>
            <div
                v-if="techTip.updated_by"
                class="px-1 md:border-l border-l-slate-400"
            >
                <strong>Updated:</strong>
                {{ techTip.updated_at }}
            </div>
        </div>
        <div v-if="techTip.public && !isPublic">
            <fa-icon icon="star" class="text-success" />
            <strong>Public Tech Tip</strong>
            <ClipboardCopy
                class="ms-4"
                tooltip="Copy Public Link to Clipboard"
                icon="link"
                :value="techTip.public_href"
            />
        </div>
    </div>
</template>
