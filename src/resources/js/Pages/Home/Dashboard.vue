<template>
    <div class="text-center">
        <BaseButton text="Add Flash Message" class="m-2" @click="pushFlash" />
        <BaseButton text="Add Message Toast" class="m-2" @click="pushToast" />
    </div>
    <div class="grid grid-cols-2 gap-2">
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
            <BaseButton class="m-2" text="Base" />
            <BaseButton class="m-2" text="Base" pill />
            <br />
            <AddButton class="m-2" />
            <AddButton class="m-2" pill />
            <br />
            <EditButton class="m-2" />
            <EditButton class="m-2" pill />
            <br />
            <DeleteButton class="m-2" />
            <DeleteButton class="m-2" pill />
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

const app = useAppStore();
const broad = useBroadcastStore();

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
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
