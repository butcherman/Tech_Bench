<template>
    <div>
        <Head title="Security Settings" />
        <div v-if="cert" class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">SSL Certificate</div>
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th class="text-end">Issued By:</th>
                                    <td>
                                        {{ issuer }}
                                        <span
                                            :class="
                                                isValid
                                                    ? 'text-success'
                                                    : 'text-danger'
                                            "
                                            :title="validTitle"
                                            v-tooltip
                                        >
                                            <fa-icon
                                                :icon="
                                                    isValid ? 'check' : 'xmark'
                                                "
                                            />
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-end">Expires:</th>
                                    <td>{{ expires }}</td>
                                </tr>
                                <tr>
                                    <th class="text-end">Organization:</th>
                                    <td>{{ organization }}</td>
                                </tr>
                                <tr>
                                    <th class="text-end">
                                        Signature Algorithm:
                                    </th>
                                    <td>{{ signature }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title">Current Certificate</div>
                            <pre v-if="cert">{{ cert }}</pre>
                            <div v-else class="text-center">
                                <h6 class="text-danger">
                                    NO SSL CERTIFICATE HAS BEEN LOADED
                                </h6>
                                <p>
                                    Please upload a valid SSL Certificate or
                                    reboot to generate a Self Signed Certificate
                                </p>
                            </div>
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
                            <EditButton class="w-100 my-1">
                                Import New Certificate
                            </EditButton>
                        </Link>
                        <Link :href="$route('admin.security.edit')">
                            <AddButton class="w-100 my-1">
                                Generate CSR
                            </AddButton>
                        </Link>
                        <DeleteButton
                            v-if="cert"
                            class="w-100 my-1"
                            @click="deleteCert"
                        >
                            Delete Current Certificate
                        </DeleteButton>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import AddButton from '@/Components/_Base/Buttons/AddButton.vue';
import EditButton from "@/Components/_Base/Buttons/EditButton.vue";
import DeleteButton from "@/Components/_Base/Buttons/DeleteButton.vue";
import { computed } from "vue";
import verify from "@/Modules/verify";
import { router } from "@inertiajs/vue3";

const props = defineProps<{
    cert: string | null;
    isValid: boolean;
    issuer: string | null;
    expires: string | null;
    signature: string | null;
    organization: string | null;
}>();

const validTitle = computed(() => {
    if (props.isValid) {
        return "This is a valid certificate";
    }

    return "The current certificate is invalid";
});

const deleteCert = () => {
    verify({
        message:
            "This operation will remove the current SSL Certificate and cannot be undone",
    }).then((res) => {
        if (res) {
            console.log("do it");

            router.delete(route("admin.security.destroy"), {
                onFinish: () => console.log("done"),
            });
        }
    });
};
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
