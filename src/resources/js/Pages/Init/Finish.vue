<script setup lang="ts">
import InitLayout from "@/Layouts/Init/InitLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import EllipsisLoader from "@/Components/_Base/Loaders/EllipsisLoader.vue";
import { ref, onMounted } from "vue";
import { dataGet } from "@/Composables/axiosWrapper.module";

const isComplete = ref<boolean>(false);
const canReboot = ref<boolean>(false);
const setupMsg = ref<string[]>([]);
const newUrl = ref<string | null>(null);

/**
 * Run the Save Setup sequence
 */
onMounted(() => {
    Echo.private("administration-channel").listen(
        ".AdministrationEvent",
        (msg: { msg: string }) => {
            setupMsg.value.push(msg.msg);

            if (msg.msg === "Setup Complete") {
                isComplete.value = true;
            }
        }
    );

    dataGet(route("init.save-setup")).then((res) => {
        if (res) {
            newUrl.value = res.data.url;
            canReboot.value = res.data.can_reboot;
            isComplete.value = true;

            redirectToLogin();
        }
    });
});

const redirectToLogin = () => {
    if (canReboot.value) {
        setTimeout(() => {
            if (newUrl.value) {
                window.location.href = `${newUrl.value?.toString()}`;
            }
        }, 60000);
    }
};
</script>

<script lang="ts">
export default { layout: InitLayout };
</script>

<template>
    <Card title="Build Tech Bench" class="tb-card">
        <div class="w-full md:w-1/2 justify-self-center">
            <h6 class="text-center">Building Application...</h6>
            <div class="ms-4 my-4 border rounded-sm p-4">
                <div v-for="msg in setupMsg">{{ msg }}</div>
                <EllipsisLoader v-if="!isComplete" />
            </div>
        </div>
        <div v-if="isComplete">
            <hr />
            <h5 class="text-center font-bold text-xl">Setup Complete!</h5>
            <div v-if="!canReboot">
                <p class="text-center">
                    You have completed the Wizard and are ready to get going.
                    Click the link below to log in again and start using Tech
                    Bench.
                </p>
                <p class="text-center">
                    The Tech Bench must be rebooted before you can log in and
                    start using the Tech Bench. Please reboot the server and
                    re-visit the login page.
                </p>
            </div>
            <div v-else>
                <p class="text-center">
                    Tech Bench is rebooting. You will be redirected to the login
                    page after the reboot is completed.
                </p>
            </div>
        </div>
    </Card>
</template>
