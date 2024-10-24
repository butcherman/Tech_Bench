<template>
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <span
                    class="float-end pointer"
                    title="Hide/Show Timeline"
                    v-tooltip
                    @click="showTimeline = !showTimeline"
                >
                    <fa-icon :icon="shrinkIcon" />
                </span>
                <AlertButton
                    v-if="!upToDate"
                    text-variant="warning"
                    title="Refresh to see new file uploaded"
                />
                <RefreshButton
                    :only="['timeline', 'uploaded-files']"
                    @loading-complete="$emit('refreshed')"
                />
                Timeline:
            </div>
            <h5 v-if="!timeline.length" class="text-center">
                No Timeline Events Yet
            </h5>
            <Transition @enter="growShow" @leave="shrinkHide" :css="false">
                <ul v-if="showTimeline" class="list-group">
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
                                v-if="!event.file_upload.length"
                                class="list-group-item"
                            >
                                <AlertButton />
                                File Deleted
                                <AlertButton />
                            </li>
                            <li
                                v-for="file in event.file_upload"
                                :key="file.file_id"
                                class="list-group-item"
                            >
                                File
                                <a :href="file.href"> {{ file.file_name }} </a>
                                added
                            </li>
                            <li
                                v-if="event.file_link_note"
                                class="list-group-item"
                            >
                                <strong>Note:</strong>
                                {{ event.file_link_note.note }}
                            </li>
                        </ul>
                    </li>
                </ul>
            </Transition>
        </div>
    </div>
</template>

<script setup lang="ts">
import RefreshButton from "../_Base/Buttons/RefreshButton.vue";
import AlertButton from "../_Base/Buttons/AlertButton.vue";
import { ref, computed } from "vue";
import { growShow, shrinkHide } from "@/Modules/Animation.module";

defineEmits(["refreshed"]);
defineProps<{
    timeline: fileLinkTimeline[];
    upToDate: boolean;
}>();

const showTimeline = ref(true);

const shrinkIcon = computed(() =>
    showTimeline.value
        ? "down-left-and-up-right-to-center"
        : "up-right-and-down-left-from-center"
);
</script>
