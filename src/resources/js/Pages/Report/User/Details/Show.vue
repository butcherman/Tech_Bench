<template>
    <div>
        <button class="btn btn-primary mb-2" @click="router.reload">
            <fa-icon icon="arrow-left" />
            Back
        </button>
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">User Details Report</div>
                        <div
                            v-for="userData in reportData"
                            :key="userData.user_id"
                            class="row"
                        >
                            <h5>{{ userData.full_name }}</h5>
                            <TableStacked :rows="userData" title-case>
                                <template #value="{ rowData }">
                                    <template
                                        v-if="rowData.index === 'last_login'"
                                    >
                                        {{
                                            rowData.value?.created_at || "Never"
                                        }}
                                    </template>
                                </template>
                            </TableStacked>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import TableStacked from "@/Components/_Base/TableStacked.vue";
import { router } from "@inertiajs/vue3";

interface report {
    user_id: number;
    username: string;
    full_name: string;
    email: string;
    role_name: string;
    created_at: string;
    updated_at: string;
    deleted_at?: string;
    password_expires?: string;
    last_login: string;
    device_tokens?: number;
    unread_notifications: number;
    total_notifications: number;
}

defineProps<{
    reportData: report[];
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
