<script setup lang="ts">
import Card from "@/core/components/Card.vue";
import prettyBytes from "pretty-bytes";

const props = defineProps<{
    storage: StorageUsage;
    status: BackupStatus;
}>();
</script>

<template>
    <Card title="Storage">
        <div class="flex flex-col justify-center h-full gap-5">
            <div>
                {{ prettyBytes(storage.used) }} /
                {{ prettyBytes(storage.total) }}
                <div
                    class="w-full bg-gray-200 rounded-full h-8 dark:bg-gray-700"
                >
                    <div
                        class="bg-blue-600 h-8 rounded-full text-md font-medium text-blue-100 text-center p-2 leading-none"
                        :style="`width: ${storage.used_percent}%`"
                    >
                        {{ storage.used_percent }}%
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <div>Available:</div>
                <div>
                    {{ prettyBytes(storage.free) }}
                </div>
                <div>Backups Using:</div>
                <div>
                    {{ prettyBytes(status.total_size) }}
                </div>
            </div>
        </div>
    </Card>
</template>
