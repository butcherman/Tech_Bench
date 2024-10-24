<template>
    <div>
        <Head title="File Link Details" />
        <div v-if="link.is_expired" class="alert alert-danger text-center">
            Link Has Expired
        </div>
        <div class="row justify-content-center my-4">
            <div class="col-md-9">
                <FileLinkDetails :link="link" :table-data="tableData" />
            </div>
            <div class="col-md-3">
                <FileLinkActions :link="link" />
            </div>
        </div>
        <div v-if="link.allow_upload" class="row justify-content-center my-4">
            <div class="col">
                <FileLinkTimeline
                    :timeline="timeline"
                    :up-to-date="upToDate"
                    @refreshed="upToDate = true"
                />
            </div>
        </div>
        <div v-if="link.allow_upload" class="row justify-content-center my-4">
            <div class="col">
                <DownloadableFiles
                    :link="link"
                    :file-list="uploadedFiles"
                    :up-to-date="upToDate"
                    @refreshed="upToDate = true"
                />
            </div>
        </div>
        <div class="row justify-content-center my-4">
            <div class="col">
                <DownloadableFiles
                    :link="link"
                    :file-list="downloadableFiles"
                    public
                />
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import FileLinkDetails from "@/Components/FileLink/FileLinkDetails.vue";
import FileLinkActions from "@/Components/FileLink/FileLinkActions.vue";
import DownloadableFiles from "@/Components/FileLink/DownloadableFiles.vue";
import FileLinkTimeline from "@/Components/FileLink/FileLinkTimeline.vue";
import { ref, onMounted, onUnmounted } from "vue";

const props = defineProps<{
    link: fileLink;
    tableData: {
        data: fileLink;
    };
    timeline: fileLinkTimeline[];
    downloadableFiles: fileLinkUpload[];
    uploadedFiles: fileLinkUpload[];
}>();

const upToDate = ref(true);

/**
 * Setup Broadcast Listener for updates on File Link
 */
onMounted(() => {
    Echo.private(`file-link.${props.link.link_id}`).listen(
        ".FileUploadedEvent",
        function () {
            upToDate.value = false;
        }
    );
});

/**
 * Leave Broadcast Channel
 */
onUnmounted(() => Echo.leave(`file-link.${props.link.link_id}`));
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
