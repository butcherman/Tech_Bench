<template>
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <RefreshButton :only="['files']" />
                Files:
                <NewFile />
            </div>
            <Overlay :loading="loading">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <td>Name</td>
                            <td>File Type</td>
                            <td>Uploaded By</td>
                            <td>Uploaded On</td>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="!files.length">
                            <td colspan="5" class="text-center">No Files</td>
                        </tr>
                        <template
                            v-for="file in files"
                            :key="file.cust_file_id"
                        >
                            <tr>
                                <td>
                                    <a
                                        :href="
                                            $route('download', [
                                                file.file_upload.file_id,
                                                file.file_upload.file_name,
                                            ])
                                        "
                                        >{{ file.name }}</a
                                    >
                                </td>
                                <td>{{ file.file_type }}</td>
                                <td>{{ file.uploaded_by }}</td>
                                <td>{{ file.updated_at }}</td>
                                <td>
                                    <EditFile v-if="permission?.files.update" :file="file" />
                                    <DeleteBadge
                                        v-if="permission?.files.delete"
                                        class="mx-2"
                                        @click="confirmDelete(file)"
                                    />
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </Overlay>
        </div>
    </div>
</template>

<script setup lang="ts">
import Overlay from "@/Components/Base/Overlay.vue";
import RefreshButton from "@/Components/Base/Buttons/RefreshButton.vue";
import NewFile from "@/Components/Customer/Files/NewFile.vue";
import EditFile from "@/Components/Customer/Files/EditFile.vue";
import DeleteBadge from "@/Components/Base/Buttons/DeleteBadge.vue";
import { ref, inject, provide } from "vue";
import { router } from "@inertiajs/vue3";
import { verifyModal } from "@/Modules/verifyModal.module";
import {
    custPermissionsKey,
    toggleFilesLoadKey,
} from "@/SymbolKeys/CustomerKeys";

const $route = route;
defineProps<{
    files: customerFile[];
}>();

const permission = inject<customerPermissions>(custPermissionsKey);

/**
 * Loading State of Component
 */
const loading = ref<boolean>(false);
const toggleLoad = () => {
    loading.value = !loading.value;
};
provide(toggleFilesLoadKey, toggleLoad);

const confirmDelete = (file: customerFile): void => {
    verifyModal("Are you sure?").then((res) => {
        if (res) {
            toggleLoad();
            router.delete(route("customers.files.destroy", file.cust_file_id), {
                preserveScroll: true,
                onFinish: () => toggleLoad(),
            });
        }
    });
};
</script>

<style lang="scss" scoped>
table thead tr td {
    font-weight: bold;
}
</style>
