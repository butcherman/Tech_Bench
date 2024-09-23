<template>
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Build Tech Bench</div>

                    <div>
                        <h6>Building Application...</h6>
                        <div class="ms-4 mt-4">
                            <div v-for="msg in setupMsg">{{ msg }}</div>
                            <EllipsisLoader v-if="!isComplete" />
                        </div>
                    </div>
                    <div v-if="isComplete">
                        <hr />
                        <h5 class="text-center">Setup Complete!</h5>
                        <p class="text-center">
                            You have completed the Wizard and are ready to get
                            going. Click the link below to log in again and
                            start using Tech Bench.
                        </p>
                        <a :href="`${newUrl}`" class="btn btn-info w-100">
                            Go To Login Page
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import EllipsisLoader from "@/Components/_Base/Loaders/EllipsisLoader.vue";
import InitLayout from "@/Layouts/InitLayout.vue";
import axios from "axios";
import { ref, onMounted } from "vue";

const isComplete = ref<boolean>(false);
const setupMsg = ref<string[]>([]);
const newUrl = ref<string | null>(null);

/**
 * Run the save setup sequence
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
    axios.get(route("init.save-setup")).then((res) => {
        newUrl.value = `${res.data.url}`;
    });
});
</script>

<script lang="ts">
export default { layout: InitLayout };
</script>

<style scoped lang="scss">
.card {
    min-height: 60vh;
}
</style>
