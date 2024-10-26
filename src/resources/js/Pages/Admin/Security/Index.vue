<template>
    <div>
        <Head title="Security Settings" />
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">SSL Certificate</div>
                        <TableStacked :rows="data" title-case align-left>
                            <template #value="{ rowData }">
                                <span v-if="rowData.index == 'is_valid'">
                                    <fa-icon
                                        :icon="
                                            rowData.value ? 'check' : 'xmark'
                                        "
                                        :class="
                                            rowData.value
                                                ? 'text-success'
                                                : 'text-danger'
                                        "
                                    />
                                </span>
                            </template>
                        </TableStacked>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center my-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Current Certificate</div>
                        <pre v-if="cert">{{ cert }}</pre>
                        <div v-else class="text-center">
                            <h6 class="text-danger">
                                NO SSL CERTIFICATE HAS BEEN LOADED
                            </h6>
                            <p>
                                Please upload a valid SSL Certificate or reboot
                                to generate a Self Signed Certificate
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <Link :href="$route('admin.security.create')">
                            <EditButton
                                class="w-100 my-1"
                                text="Import Certificate"
                            />
                        </Link>
                        <Link
                            :href="
                                $route('admin.security.edit', 'Generate CSR')
                            "
                        >
                            <AddButton class="w-100 my-1" text="Generate CSR" />
                        </Link>
                        <DeleteButton
                            class="w-100 my-1"
                            text="Delete Certificate"
                            @click="deleteCert"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import TableStacked from "@/Components/_Base/TableStacked.vue";
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import EditButton from "@/Components/_Base/Buttons/EditButton.vue";
import DeleteButton from "@/Components/_Base/Buttons/DeleteButton.vue";
import verifyModal from "@/Modules/verifyModal";
import { router } from "@inertiajs/vue3";

defineProps<{
    cert: string | null;
    data:
        | {
              is_valid: boolean;
              issuer: string;
              expires: string;
              signature: string;
              organization: string;
          }
        | boolean;
}>();

const deleteCert = () => {
    verifyModal(
        "This operation will remove the current Certificate and cannot be undone"
    ).then((res) => {
        if (res) {
            router.delete(route("admin.security.destroy", "destroy-cert"));
        }
    });
};
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
