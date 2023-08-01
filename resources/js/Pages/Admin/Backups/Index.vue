<template>
    <div>
        <Head title="Backups" />
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <button
                            class="btn btn-info w-100"
                            @click="mountBackupProcess"
                        >
                            Run Local Backup
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Backup Output:</div>
                        <div class="border" id="backup-output-box">
                            <pre ref="backupOutput" class="m-2">
No Backup Running</pre
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import axios from "axios";
import { echo } from "@/State/LayoutState";
import { ref, reactive, onMounted } from "vue";

// const props = defineProps<{}>();

const backupOutput = ref<InstanceType<typeof HTMLElement> | null>(null);

/**
 * On Mount, subscribe to the Backup Status Channel.
 */
onMounted(() => {
    echo.private("process.backup").listen('.BroadcastBackupStatus', (data) => {
        console.log(data);

        if (backupOutput.value) {
            backupOutput.value.innerHTML += `${data.message}\n`;
        }
    });
});

const mountBackupProcess = () => {
    console.log("mounting backup");

    axios
        .put(route("admin.backups.run"))
        .then((res) => {
            console.log(res);
            if (res.status === 204 && backupOutput.value) {
                backupOutput.value.innerHTML = "Starting Backup...\n\n";
            }
        })
        .catch((err) => console.log(err));
};
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<style scoped lang="scss">
#backup-output-box {
    height: 250px;
}
</style>
