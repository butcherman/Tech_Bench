<script setup lang="ts">
import Card from "@/Components/_Base/Card.vue";
import LinkLayout from "@/Layouts/FileLink/LinkLayout.vue";
import Overlay from "@/Components/_Base/Loaders/Overlay.vue";
import prettyBytes from "pretty-bytes";
import PublicFileForm from "@/Forms/FileLink/PublicFileForm.vue";
import ResourceList from "@/Components/_Base/ResourceList.vue";
import { computed, ref } from "vue";
import { router } from "@inertiajs/vue3";
import { getIconFromFilename } from "@/Composables/fileIcons.module";

const props = defineProps<{
    link: fileLink;
    files: fileUpload[];
}>();

const isLoading = ref(false);
router.on("start", () => (isLoading.value = true));
router.on("finish", () => (isLoading.value = false));

/**
 * If no information is shared, show error notification
 */
const blankLink = computed(() => {
    if (
        !props.link.instructions &&
        !props.link.allow_upload &&
        !props.files.length
    ) {
        return true;
    }

    return false;
});
</script>

<script lang="ts">
export default { layout: LinkLayout };
</script>

<template>
    <div class="flex flex-col items-center">
        <Card v-if="blankLink" class="tb-card">
            <div class="flex">
                <img src="/images/error/oops.png" alt="oops" />
                <div class="flex flex-col justify-center">
                    <h5 class="text-center">Well this is embarrassing...</h5>
                    <p class="text-center px-5">
                        It seems you were sent a link with nothing in it. Please
                        contact the person who provided the link for additional
                        information.
                    </p>
                </div>
            </div>
        </Card>
        <Card v-if="link.instructions" class="tb-card">
            <div v-html="link.instructions" />
        </Card>
        <Card v-if="files.length" class="tb-card" title="Available Files">
            <ResourceList :list="files" compact hover-row>
                <template #list-item="{ item }">
                    <a :href="item.href" class="block text-center">
                        <span
                            v-html="getIconFromFilename(item.file_name)"
                            class="me-1"
                        />
                        {{ item.file_name }}
                        ({{ prettyBytes(item.file_size) }})
                    </a>
                </template>
            </ResourceList>
        </Card>
        <Card v-if="link.allow_upload" class="tb-card" title="Upload File">
            <Overlay :loading="isLoading">
                <PublicFileForm :link="link" />
            </Overlay>
        </Card>
    </div>
</template>
