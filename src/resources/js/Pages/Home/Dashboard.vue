<template>
    <div class="text-center">
        <BaseButton text="Add Flash Message" class="m-2" @click="pushFlash" />
        <BaseButton text="Add Message Toast" class="m-2" @click="pushToast" />
        <BaseButton
            text="Open Modal"
            class="m-2"
            @click="dashboardModal?.show"
        />
    </div>
    <Modal ref="dashboardModal" title="Example Modal">
        <LogoImage dark-header />
        <template #footer>
            <h3 class="text-center w-full">Test Footer</h3>
        </template>
    </Modal>
    <div class="grid md:grid-cols-3 gap-2">
        <Card title="Typography">
            <h1>H1 Header</h1>
            <h2>H2 Header</h2>
            <h3>H3 Header</h3>
            <h4>H4 Header</h4>
            <h5>H5 Header</h5>
            <h6>H6 Header</h6>
            <p>Paragraph</p>
            <code>Code Block</code>
            <pre>Pre Block</pre>
        </Card>
        <Card title="Buttons">
            <BaseButton class="m-2" text="Button" />
            <BaseButton class="m-2" text="Pill Button" pill />

            <br />
            <AddButton class="m-2" />
            <AddButton class="m-2" pill />
            <AddBadge class="m-2" />
            <br />
            <EditButton class="m-2" />
            <EditButton class="m-2" pill />
            <EditBadge class="m-2" />
            <br />
            <DeleteButton
                class="m-2"
                confirm-msg="This Cannot Be Undone"
                confirm
                @accepted="console.log('accepted')"
            />
            <DeleteButton class="m-2" pill />
            <DeleteBadge class="m-2" />
            <br />
            <BaseBadge
                icon="house"
                v-tooltip="'Base Badge'"
                :href="$route('about')"
                class="m-2"
            />
            <ClipboardCopy value="Random Text for Clipboard" class="m-2" />
            <RefreshButton class="m-2" />
        </Card>
        <Card title="Resource List">
            <ResourceList :list="resourceListData" />
        </Card>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import EditButton from "@/Components/_Base/Buttons/EditButton.vue";
import DeleteButton from "@/Components/_Base/Buttons/DeleteButton.vue";
import Card from "@/Components/_Base/Card.vue";
import { useAppStore } from "@/Stores/AppStore";
import { useBroadcastStore } from "@/Stores/BroadcastStore";
import { ref } from "vue";
import BaseBadge from "@/Components/_Base/Badges/BaseBadge.vue";
import AddBadge from "@/Components/_Base/Badges/AddBadge.vue";
import EditBadge from "@/Components/_Base/Badges/EditBadge.vue";
import DeleteBadge from "@/Components/_Base/Badges/DeleteBadge.vue";
import ClipboardCopy from "@/Components/_Base/ClipboardCopy.vue";
import RefreshButton from "@/Components/_Base/Buttons/RefreshButton.vue";
import Modal from "@/Components/_Base/Modal.vue";
import LogoImage from "@/Components/_Base/LogoImage.vue";
import ResourceList from "@/Components/_Base/ResourceList.vue";

const app = useAppStore();
const broad = useBroadcastStore();

const dashboardModal = ref<InstanceType<typeof Modal> | null>(null);

const index = ref(0);
const pushFlash = () => {
    console.log("message pushed");
    app.pushFlashMsg({
        type: "warning",
        message: `Message - ${index.value}`,
    });
    index.value++;
};

const pushToast = () => {
    console.log("pushing toast");
    broad.pushToastMsg(`Message - ${index.value}`, "A Title", route("about"));
    index.value++;
};

const resourceListData = ref([
    {
        icon: "pencil",
        text: "This is Text",
        link: route("about"),
    },
    {
        icon: "pencil",
        text: "This is Text 1",
    },
    {
        icon: "pencil",
        text: "This is Text 2",
    },
    {
        icon: "pencil",
        text: "This is Text 3",
    },
    {
        icon: "pencil",
        text: "This is Text 4",
    },
]);
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
