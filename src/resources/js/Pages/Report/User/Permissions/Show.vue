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
                        <div class="card-title">User Permissions Report</div>
                        <div
                            v-for="dataItem in reportData.data"
                            :key="dataItem.user_id"
                            class="row"
                        >
                            <h5>{{ dataItem.full_name }}</h5>
                            <p class="m-0 p-0">
                                <strong>Role:</strong> {{ dataItem.role_name }}
                            </p>
                            <p class="mb-2 p-0">
                                <strong>Status:</strong>
                                {{ dataItem.status ? "Active" : "Disabled" }}
                            </p>
                            <div
                                v-for="(group, name) in permissionList"
                                :key="name"
                                class="row"
                            >
                                <h6>{{ name }}</h6>
                                <div
                                    v-for="opt in group"
                                    :key="opt.perm_type_id"
                                    class="col-12 col-lg-4"
                                >
                                    <fa-icon
                                        v-if="
                                            getPermValue(
                                                dataItem,
                                                opt.perm_type_id
                                            )
                                        "
                                        icon="check"
                                        class="text-success"
                                    />
                                    <fa-icon
                                        v-else
                                        icon="xmark"
                                        class="text-danger"
                                    />
                                    {{ opt.description }}
                                </div>
                            </div>
                            <hr />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import { router } from "@inertiajs/vue3";

interface report {
    user_id: number;
    full_name: string;
    status: boolean;
    role_name: string;
    role_description: string;
    permissions: userRolePermission[];
}

defineProps<{
    reportData: { data: report[] };
    permissionList: userRolePermissionGroup;
}>();

const getPermValue = (userData: report, permTypeId: number) => {
    return userData.permissions.find((item) => item.perm_type_id == permTypeId)
        ?.allow;
};
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
