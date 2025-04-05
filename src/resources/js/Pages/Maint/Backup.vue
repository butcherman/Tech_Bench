<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import BaseBadge from "@/Components/_Base/Badges/BaseBadge.vue";
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import Card from "@/Components/_Base/Card.vue";
import DeleteBadge from "@/Components/_Base/Badges/DeleteBadge.vue";
import Modal from "@/Components/_Base/Modal.vue";
import prettyBytes from "pretty-bytes";
import ResourceList from "@/Components/_Base/ResourceList.vue";
import UploadBackupForm from "@/Forms/Maintenance/UploadBackupForm.vue";
import { onMounted, ref, useTemplateRef } from "vue";
import { router } from "@inertiajs/vue3";

defineProps<{
    backupList: any[];
}>();

const uploadModal = useTemplateRef("upload-backup-modal");
const backupIsRunning = ref<boolean>(false);
const backupMessages = ref<string[]>([]);

onMounted(() => {
    Echo.private("administration-channel").listen(
        ".AdministrationEvent",
        (msg: { msg: string }) => {
            backupMessages.value.push(msg.msg);

            if (msg.msg === "Backup Process Called") {
                backupIsRunning.value = true;
            }

            if (msg.msg == "Backup completed!") {
                backupIsRunning.value = false;
                handleBackupUploaded();
            }
        }
    );
});

/**
 * Reload the page after a backup file is uploaded
 */
const handleBackupUploaded = () => {
    uploadModal.value?.hide();
    router.reload();
};
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div class="flex justify-center">
        <Card class="tb-card text-center">
            <BaseButton
                class="w-3/4 my-1"
                text="Run Backup"
                :disabled="backupIsRunning"
                :href="$route('maint.backups.run-backup')"
                async
            />
            <BaseButton
                class="w-3/4 my-1"
                text="Backup Settings"
                icon="cog"
                :href="$route('maint.backups.settings.show')"
            />
        </Card>
    </div>
    <div class="flex justify-center">
        <Card class="tb-card" title="Backup Messages">
            <div class="bg-black text-white rounded-lg p-5 h-52 overflow-auto">
                <div v-if="!backupMessages.length">
                    <pre>$ No Messages</pre>
                </div>
                <div v-for="msg in backupMessages" class="px-5">
                    <pre>{{ msg }}</pre>
                </div>
            </div>
        </Card>
    </div>
    <div class="flex justify-center">
        <Card class="tb-card" title="Backup Files">
            <div>
                <ResourceList
                    :list="backupList"
                    label-field="name"
                    empty-text="No Backups Found"
                >
                    <template #list-item="{ item }">
                        {{ item.name }}
                        <span class="text-sm">
                            ({{ prettyBytes(item.size) }})
                        </span>
                    </template>
                    <template #actions="{ item }">
                        <a
                            :href="$route('maint.backups.download', item.name)"
                            v-tooltip="'Download Backup'"
                        >
                            <BaseBadge icon="download" class="mx-1" />
                        </a>
                        <DeleteBadge
                            class="mx-1"
                            :href="$route('maint.backups.delete', item.name)"
                            confirm
                            delete-method
                        />
                    </template>
                </ResourceList>
                <div class="text-center">
                    <BaseButton
                        class="w-3/4 my-2"
                        icon="upload"
                        text="Upload Backup File"
                        @click="uploadModal?.show()"
                    />
                </div>
                <div class="text-center text-sm">Use CLI to Restore Backup</div>
            </div>
        </Card>
        <Modal ref="upload-backup-modal" title="Upload Backup File">
            <UploadBackupForm @success="handleBackupUploaded" />
        </Modal>
    </div>
</template>
