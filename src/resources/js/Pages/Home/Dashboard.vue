<template>
    <div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <p class="text-center">Dashboard</p>
                        <div class="text-center">
                            <button
                                class="btn btn-info m-2"
                                @click="newFlashMsg"
                            >
                                Push Flash Msg
                            </button>
                            <button
                                class="btn btn-info m-2"
                                @click="
                                    pushToastMsg('Test Toast', 'Test Title')
                                "
                            >
                                Push Notification Toast
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import { useAppStore } from "@/Store/AppStore";
import { ref, reactive, onMounted } from "vue";

// const props = defineProps<{}>();

// const app = useAppStore();
const { pushFlashMsg, pushToastMsg } = useAppStore();

const count = ref(1);
const newFlashMsg = () => {
    console.log("pushing flash message");
    pushFlashMsg({
        id: count.value.toString(),
        type: "success",
        message: `Testing ${count.value}`,
    });
    count.value++;
};
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
