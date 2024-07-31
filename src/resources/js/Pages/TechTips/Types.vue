<template>
    <div>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <p>
                            When a tech tip is created, a "Tech Tip Type" must
                            be selected. This helps to determine the purpose of
                            the Tech Tip at a quick glance. Common options are:
                        </p>
                        <div class="d-flex justify-content-center">
                            <ul class="d-block">
                                <li>Tech Tip (generic kb article)</li>
                                <li>
                                    Documentation (official manufacturer
                                    documentation)
                                </li>
                                <li>
                                    Software (applications for this Tech Tip)
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center my-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Existing Tech Tip Types</div>
                        <ul class="list-group">
                            <li
                                v-for="type in tipTypes"
                                :key="type.tip_type_id"
                                class="list-group-item"
                            >
                                {{ type.description }}
                                <span class="float-end">
                                    <EditBadge @click="openEditModal(type)" />
                                    <DeleteBadge @click="verifyDelete(type)" />
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center my-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Create New Tip Type</div>
                        <TechTipTypeForm />
                    </div>
                </div>
            </div>
        </div>
        <Modal
            ref="editTypeModal"
            title="Edit Tech Tip Type"
            @hidden="editType = null"
        >
            <TechTipTypeForm
                v-if="editType"
                :tip-type="editType"
                @success="closeEditModal"
            />
        </Modal>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import EditBadge from "@/Components/_Base/Badges/EditBadge.vue";
import DeleteBadge from "@/Components/_Base/Badges/DeleteBadge.vue";
import TechTipTypeForm from "@/Forms/TechTips/TechTipTypeForm.vue";
import Modal from "@/Components/_Base/Modal.vue";
import verifyModal from "@/Modules/verifyModal";
import { ref } from "vue";
import { router } from "@inertiajs/vue3";

defineProps<{
    tipTypes: tipType[];
}>();

const editTypeModal = ref<InstanceType<typeof Modal> | null>(null);
const editType = ref<tipType | null>(null);

const openEditModal = (type: tipType) => {
    editType.value = type;
    editTypeModal.value?.show();
};

const closeEditModal = () => {
    editType.value = null;
    editTypeModal.value?.hide();
};

const verifyDelete = (type: tipType) => {
    verifyModal("Do you want to delete this Tech Tip Type?").then((res) => {
        if (res) {
            router.delete(
                route("admin.tech-tips.tip-types.destroy", type.tip_type_id)
            );
        }
    });
};
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
