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
                        <h5 class="text-center">
                            Well this is embarrassing...
                        </h5>
                        <p class="text-center">
                            It seems you were sent a link with nothing in it.
                            Please contact the person who provided the link for
                            additional information.
                        </p>
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
    </div>
</template>

<script setup lang="ts">
import KbLayout from "@/Layouts/KbLayout.vue";
import PublicFileLinkForm from "@/Forms/FileLink/PublicFileLinkForm.vue";
import { ref, reactive, onMounted, computed } from "vue";
import { useAppStore } from "@/Store/AppStore";

const props = defineProps<{
    linkData: fileLink;
    linkFiles: fileUpload[];
}>();

const app = useAppStore();
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
