<script setup lang="ts">
import BackupSettingsForm from "../forms/BackupSettingsForm.vue";
import BaseButton from "@/core/components/buttons/BaseButton.vue";
import Card from "@/core/components/Card.vue";
import Modal from "@/core/components/Modal.vue";
import { ref } from "vue";

const props = defineProps<{
    settings: BackupSettings;
    strategy: BackupStrategy;
}>();

const showSettingsForm = ref(false);

const getBooleanIcon = (boolVal: boolean): string => {
    return boolVal ? "check" : "xmark";
};

const getBooleanColor = (boolVal: boolean): string => {
    return boolVal ? "text-success" : "text-danger";
};
</script>

<template>
    <Card title="Backup Configuration">
        <div class="grid grid-cols-2 gap-3">
            <div>Nightly Backups:</div>
            <div>
                <fa-icon
                    :icon="getBooleanIcon(settings.nightly_backup)"
                    :class="getBooleanColor(settings.nightly_backup)"
                />
            </div>
            <div>Nightly Cleanup:</div>
            <div>
                <fa-icon
                    :icon="getBooleanIcon(settings.nightly_cleanup)"
                    :class="getBooleanColor(settings.nightly_cleanup)"
                />
            </div>
            <div>Destination:</div>
            <div>Local</div>
            <div>Encryption:</div>
            <div>
                <fa-icon
                    :icon="getBooleanIcon(settings.encryption)"
                    :class="getBooleanColor(settings.encryption)"
                />
            </div>
            <div>Retention:</div>
            <div>{{ Object.values(strategy).join(" / ") }}</div>
        </div>
        <div class="mt-4">
            <BaseButton
                text="Edit Configuration"
                size="sm"
                @click="showSettingsForm = true"
            />
        </div>
        <Modal v-model="showSettingsForm" title="Backup Settings" size="sm">
            <BackupSettingsForm :settings @success="showSettingsForm = false" />
        </Modal>
    </Card>
</template>
