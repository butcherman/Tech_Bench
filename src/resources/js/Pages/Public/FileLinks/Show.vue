<template>
    <div>
        <div class="row">
            <div class="col-12">
                <h4 class="text-center text-md-left">{{ app.name }}</h4>
            </div>
        </div>
        <div v-if="blankLink" class="row justify-content-center my-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div v-if="upToDate">
                            <h5 class="text-center">
                                Well this is embarrassing...
                            </h5>
                            <p class="text-center">
                                It seems you were sent a link with nothing in
                                it. Please contact the person who provided the
                                link for additional information.
                            </p>
                        </div>
                        <div v-else>
                            <h5 class="text-center">
                                <AlertButton />
                                New File Available
                                <AlertButton />
                            </h5>
                            <p class="text-center">
                                <RefreshButton
                                    :only="['link-files']"
                                    @loading-complete="upToDate = true"
                                />
                                Please Refresh Page to view new data
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div
            v-if="linkData.instructions"
            class="row justify-content-center my-4"
        >
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Instructions</div>
                        <div v-html="linkData.instructions" />
                    </div>
                </div>
            </div>
        </div>
        <div v-if="linkFiles.length" class="row justify-content-center my-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">
                            <span v-if="!upToDate">
                                <AlertButton title="New File Available" />
                                <RefreshButton
                                    :only="['link-files']"
                                    @loading-complete="upToDate = true"
                                />
                            </span>
                            You have files available to download
                        </div>
                        <ul class="list-group">
                            <li
                                v-for="file in linkFiles"
                                :key="file.file_id"
                                class="list-group-item"
                            >
                                <a :href="file.href">{{ file.file_name }}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div
            v-if="linkData.allow_upload"
            class="row justify-content-center my-4"
        >
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Upload a file</div>
                        <PublicFileLinkForm :link-hash="linkData.link_hash" />
                    </div>
                </div>
            </div>
        </div>
        <AppNotificationToast ref="toast" />
    </div>
</template>

<script setup lang="ts">
import KbLayout from "@/Layouts/KbLayout.vue";
import AppNotificationToast from "@/Layouts/AppLayout/AppNotificationToast.vue";
import PublicFileLinkForm from "@/Forms/Public/FileLink/PublicFileLinkForm.vue";
import AlertButton from "@/Components/_Base/Buttons/AlertButton.vue";
import RefreshButton from "@/Components/_Base/Buttons/RefreshButton.vue";
import { ref, onMounted, computed, onUnmounted } from "vue";
import { useAppStore } from "@/Store/AppStore";

const props = defineProps<{
    linkData: fileLink;
    linkFiles: fileUpload[];
}>();

const app = useAppStore();

/**
 * If no information is shared, show error notification
 */
const blankLink = computed(() => {
    if (
        !props.linkData.instructions &&
        !props.linkData.allow_upload &&
        !props.linkFiles.length
    ) {
        return true;
    }

    return false;
});

/**
 * Determine if the DOM is up to date with latest DB information
 */
const upToDate = ref(true);

/**
 * Broadcast Notifications for new information
 */
const toast = ref<InstanceType<typeof AppNotificationToast> | null>(null);
onMounted(() => {
    Echo.channel(`file-links.${props.linkData.link_hash}`).listen(
        ".FileUploadedEvent",
        (message: toastData) => {
            toast.value?.pushMessage(message.message, message.title);
            upToDate.value = false;
        }
    );
});

onUnmounted(() => Echo.leave(`file-links.${props.linkData.link_hash}`));
</script>

<script lang="ts">
export default { layout: KbLayout };
</script>

<style scoped lang="scss">
.list-group-item {
    padding: 0;
    a {
        color: #000000;
        display: block;
        width: 100%;
        height: 100%;
        padding: 0.5em;
        text-decoration: none;
        &:hover {
            background-color: #999999;
        }
    }
}
</style>
