<template>
    <Modal ref="fileDetailsModal" title="Customer File Details">
        <!-- <h1>Hello World</h1> -->
        <!-- {{ activeFile }} -->
        <div class="table-responsive">
            <table class="table">
                <tbody>
                    <tr>
                        <th class="text-end">Name:</th>
                        <td>{{ activeFile?.name }}</td>
                    </tr>
                    <tr>
                        <th class="text-end">Uploaded By:</th>
                        <td>{{ activeFile?.uploaded_by }}</td>
                    </tr>
                    <tr>
                        <th class="text-end">Uploaded On:</th>
                        <td>{{ activeFile?.created_at }}</td>
                    </tr>
                    <tr>
                        <th class="text-end">File Name:</th>
                        <td>{{ activeFile?.file_upload.file_name }}</td>
                    </tr>
                    <tr>
                        <th class="text-end">File Size:</th>
                        <td>
                            {{ fileSize }}
                        </td>
                    </tr>
                    <!-- <tr
                        v-if="
                            activeFile?.equip_name ||
                            activeFile?.customer_site.length
                        "
                    >
                        <th class="text-end">File For:</th>
                        <td>???</td>
                    </tr> -->
                </tbody>
            </table>
        </div>
    </Modal>
</template>

<script setup lang="ts">
import Modal from "../_Base/Modal.vue";
import prettyBytes from "pretty-bytes";
import { ref, computed } from "vue";

const fileDetailsModal = ref<InstanceType<typeof Modal> | null>(null);
const activeFile = ref<customerFile>();

const show = (fileData: customerFile) => {
    activeFile.value = fileData;
    fileDetailsModal.value?.show();
};

const fileSize = computed(() => {
    if (activeFile.value?.file_upload.file_size === -1) {
        return "unknown";
    }

    return prettyBytes(activeFile.value?.file_upload.file_size || 0);
});

defineExpose({ show });
</script>
