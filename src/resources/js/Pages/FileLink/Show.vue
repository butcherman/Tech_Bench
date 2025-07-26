<script setup lang="ts">
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import AddFileModal from "@/Components/FileLink/AddFileModal.vue";
import AppLayout from "@/Layouts/App/AppLayout.vue";
import AtomLoader from "@/Components/_Base/Loaders/AtomLoader.vue";
import Card from "@/Components/_Base/Card.vue";
import LinkActions from "@/Components/FileLink/LinkActions.vue";
import LinkDetails from "@/Components/FileLink/LinkDetails.vue";
import LinkFileList from "@/Components/FileLink/LinkFileList.vue";
import LinkTimeline from "@/Components/FileLink/LinkTimeline.vue";
import MoveFile from "@/Components/FileLink/MoveFile.vue";
import RefreshButton from "@/Components/_Base/Buttons/RefreshButton.vue";
import { Deferred } from "@inertiajs/vue3";
import { Message } from "primevue";
import { useTemplateRef } from "vue";

defineProps<{
    link: fileLink;
    timeline?: fileLinkTimeline[];
    uploads?: fileLinkFile[];
    downloads?: fileLinkFile[];
    isAdmin?: boolean;
}>();

const moveModal = useTemplateRef("move-file-modal");
const addModal = useTemplateRef("add-file-modal");
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div class="flex flex-col gap-3">
        <Message
            v-if="link.is_expired"
            class="my-3"
            severity="error"
            size="large"
            pt:content:class="w-full text-center block"
            pt:text:class="w-full"
        >
            <div class="flex px-4">
                <fa-icon class="pt-1" icon="triangle-exclamation" />
                <span class="grow"> Link Has Expired </span>
                <fa-icon class="pt-1" icon="triangle-exclamation" />
            </div>
        </Message>
        <div class="flex flex-col md:flex-row gap-3">
            <Card class="md:basis-2/3" title="Details">
                <LinkDetails :link="link" />
            </Card>
            <Card class="grow">
                <LinkActions :link="link" :is-admin="isAdmin" />
            </Card>
        </div>
        <LinkTimeline :timeline="timeline" />
        <Card>
            <template #title>
                <RefreshButton :only="['timeline', 'uploads']" />
                Uploaded Files
            </template>
            <Deferred data="uploads">
                <template #fallback>
                    <div class="flex justify-center">
                        <AtomLoader />
                    </div>
                </template>
                <template v-if="uploads">
                    <LinkFileList
                        :link="link"
                        :file-list="uploads"
                        :timeline="timeline"
                        is-upload
                        @move="moveModal?.triggerMove"
                    />
                </template>
            </Deferred>
        </Card>
        <Card>
            <template #title>
                <RefreshButton :only="['timeline', 'downloads']" />
                Downloadable Files
            </template>
            <template #append-title>
                <AddButton
                    size="small"
                    text="Add File"
                    pill
                    @click="addModal?.show()"
                />
            </template>
            <Deferred data="downloads">
                <template #fallback>
                    <div class="flex justify-center">
                        <AtomLoader />
                    </div>
                </template>
                <template v-if="downloads">
                    <LinkFileList
                        :link="link"
                        :file-list="downloads"
                        :timeline="timeline"
                        @move="moveModal?.triggerMove"
                    />
                </template>
            </Deferred>
        </Card>
        <MoveFile :link="link" ref="move-file-modal" />
        <AddFileModal :link="link" ref="add-file-modal" />
    </div>
</template>
