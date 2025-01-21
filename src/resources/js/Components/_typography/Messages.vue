import Card from '@/Components/_Base/Card.vue';
<template>
    <Card title="Messages and Flash Alerts">
        <div class="text-center">
            <BaseButton
                text="Add Flash Message"
                class="m-2"
                @click="pushFlash"
            />
            <BaseButton
                text="Add Message Toast"
                class="m-2"
                @click="pushToast"
            />
            <BaseButton
                text="Example Modal"
                class="m-2"
                @click="dashboardModal?.show"
            />
            <BaseButton
                text="OK Prompt"
                class="m-2"
                @click="okModal('Example OK Prompt', true)"
            />
        </div>
        <Modal ref="dashboardModal" title="Example Modal">
            <LogoImage dark-header> </LogoImage>
            <template #footer>
                <div class="mt-2">
                    <BaseButton
                        text="Clicky Yes"
                        variant="success"
                        class="mx-1"
                        @click="dashboardModal?.hide"
                    />
                    <BaseButton
                        text="Clicky No"
                        variant="danger"
                        class="mx-1"
                        @click="dashboardModal?.hide"
                    />
                </div>
            </template>
        </Modal>
    </Card>
</template>

<script setup lang="ts">
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import Card from "@/Components/_Base/Card.vue";
import LogoImage from "@/Components/_Base/LogoImage.vue";
import Modal from "@/Components/_Base/Modal.vue";
import okModal from "@/Modules/okModal";
import { ref } from "vue";
import { useAppStore } from "../../Stores/AppStore";
import { useBroadcastStore } from "../../Stores/BroadcastStore";

const app = useAppStore();
const broad = useBroadcastStore();

const dashboardModal = ref<InstanceType<typeof Modal> | null>(null);
const index = ref(0);
const flashTypes: alertMessageType[] = [
    "danger",
    "dark",
    "error",
    "help",
    "info",
    "light",
    "primary",
    "secondary",
    "success",
    "warning",
];

/**
 * Push a test Flash Message.  Message Type will be chosen at random.
 */
const pushFlash = () => {
    let msgTypeIndex = Math.floor(Math.random() * flashTypes.length);

    app.pushFlashMsg({
        type: flashTypes[msgTypeIndex],
        message: `This is a ${flashTypes[msgTypeIndex]} alert`,
    });
    index.value++;
};

/**
 * Push a test Notification Toast
 */
const pushToast = () => {
    broad.pushToastMsg(`Message - ${index.value}`, "A Title", route("about"));
    index.value++;
};
</script>
