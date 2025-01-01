<template>
    <div>
        <Card title="Customer File Types" class="tb-card">
            <v-list>
                <v-list-item
                    v-for="type in fileTypes"
                    :key="type.file_type_id"
                    class="border-b"
                >
                    <span class="float-end">
                        <EditBadge @click="editModal?.show()" />
                        <DeleteBadge />
                    </span>
                    {{ type.description }}
                </v-list-item>
            </v-list>
        </Card>
        <Modal ref="editModal" title="Edit File Type">
            <FileTypeForm />
        </Modal>
    </div>
</template>

<script setup lang="ts">
import DeleteBadge from "@/Components/_Base/Badges/DeleteBadge.vue";
import EditBadge from "@/Components/_Base/Badges/EditBadge.vue";
import Card from "@/Components/_Base/Card.vue";
import Modal from "@/Components/_Base/Modal.vue";
import FileTypeForm from "@/Forms/Admin/FileTypeForm.vue";
import AppLayout from "@/Layouts/App/AppLayout.vue";
import { ref, reactive, onMounted } from "vue";

defineProps<{
    fileTypes: customerFileType[];
}>();

const editModal = ref<InstanceType<typeof Modal> | null>(null);
const activeType = ref<customerFileType | undefined>(undefined);
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
