<template>
    <Head title="View Role" />
    <Overlay :loading="loading">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Role Data</div>
                        <h5 class="text-center">{{ role.name }}</h5>
                        <p class="text-center">{{ role.description }}</p>
                        <div class="border-top">
                            <div
                                v-for="(group, name) in permissions"
                                :key="name"
                                class="row"
                            >
                                <h6>{{ name }}</h6>
                                <div
                                    class="col-12 col-lg-4"
                                    v-for="opt in group"
                                    :key="opt.perm_type_id"
                                >
                                    <fa-icon
                                        :icon="
                                            opt.allow ? 'fa-check' : 'fa-xmark'
                                        "
                                        :class="
                                            opt.allow
                                                ? 'text-success'
                                                : 'text-danger'
                                        "
                                    />
                                    {{ opt.description }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="text-center">
                            <Link
                                as="button"
                                :href="
                                    $route(
                                        'admin.user-roles.copy',
                                        role.role_id
                                    )
                                "
                                type="button"
                                class="btn btn-info w-100 m-1"
                            >
                                <fa-icon icon="copy" class="me-1" />
                                Copy Role
                            </Link>
                        </div>
                        <div class="text-center">
                            <Link
                                as="button"
                                :href="
                                    $route(
                                        'admin.user-roles.edit',
                                        role.role_id
                                    )
                                "
                                type="button"
                                class="btn btn-warning w-100 m-1"
                                :disabled="!role.allow_edit"
                            >
                                <fa-icon icon="edit" class="me-1" />
                                {{
                                    !role.allow_edit
                                        ? "Cannot Edit A Default Role"
                                        : "Edit Role"
                                }}
                            </Link>
                        </div>
                        <div class="text-center">
                            <button
                                type="button"
                                class="btn btn-danger w-100 m-1"
                                :disabled="!role.allow_edit"
                                @click="deleteRole"
                            >
                                <fa-icon icon="trash-alt" class="me-1" />
                                {{
                                    !role.allow_edit
                                        ? "Cannot Delete A Default Role"
                                        : "Delete Role"
                                }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Overlay>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import Overlay from "@/Components/_Base/Loaders/Overlay.vue";
import verify from "@/Modules/verify";
import { router } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps<{
    role: userRole;
    permissions: { [key: string]: any[] };
}>();

const loading = ref(false);

const deleteRole = () => {
    verify({
        message: "This operation cannot be undone",
    }).then((res) => {
        if (res) {
            loading.value = true;
            router.delete(
                route("admin.user-roles.destroy", props.role.role_id),
                {
                    onFinish: () => (loading.value = false),
                }
            );
        }
    });
};
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
