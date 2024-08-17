<template>
    <div class="card">
        <div class="card-body">
            <div class="card-title">Timeline:</div>
            <h5 v-if="!timeline.length" class="text-center">
                No Timeline Events Yet
            </h5>
            <ul class="list-group">
                <li
                    v-for="event in timeline"
                    :key="event.timeline_id"
                    class="list-group-item"
                >
                    <div><strong>Date:</strong> {{ event.created_at }}</div>
                    <div v-if="isNaN(event.added_by as any)">
                        <strong>Created By:</strong> {{ event.added_by }}
                    </div>
                    <ul class="ms-4 list-group">
                        <li
                            v-for="file in event.file_upload"
                            :key="file.file_id"
                            class="list-group-item"
                        >
                            File
                            <a :href="file.href"> {{ file.file_name }} </a>
                            added
                        </li>
                        <li v-if="event.file_link_note" class="list-group-item">
                            <strong>Note:</strong>
                            {{ event.file_link_note.note }}
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</template>

<script setup lang="ts">
defineProps<{
    timeline: fileLinkTimeline[];
}>();
</script>
