<template>
    <div>
        <Head title="Equipment Categories and Types" />
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">
                            Equipment Categories and Types
                        </div>

                        <div v-if="!equipmentList.length" class="text-center">
                            <p>
                                You have not created any equipment profiles yet
                            </p>
                            <p>
                                Click the Create New button below to get started
                            </p>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <template
                                        v-for="cat in equipmentList"
                                        :key="cat.cat_id"
                                    >
                                        <tr>
                                            <th>{{ cat.name }}</th>
                                            <td class="text-end">
                                                <EditBadge
                                                    tooltip="Edit Category Name"
                                                    @click="openEditModal(cat)"
                                                />
                                                <DeleteBadge
                                                    v-if="
                                                        !cat.equipment_type
                                                            .length
                                                    "
                                                    tooltip="Delete Category"
                                                    @click="
                                                        verifyDeleteCategory(
                                                            cat
                                                        )
                                                    "
                                                />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div
                                                    v-if="
                                                        !cat.equipment_type
                                                            .length
                                                    "
                                                    class="text-center"
                                                >
                                                    No Equipment has been
                                                    created for this Category
                                                </div>
                                                <table
                                                    class="table table-striped table-hover mx-1"
                                                >
                                                    <tbody>
                                                        <tr
                                                            v-for="equip in cat.equipment_type"
                                                            :key="
                                                                equip.equip_id
                                                            "
                                                            class="row-link"
                                                        >
                                                            <td>
                                                                <Link
                                                                    :href="
                                                                        $route(
                                                                            'equipment.edit',
                                                                            equip.equip_id
                                                                        )
                                                                    "
                                                                >
                                                                    {{
                                                                        equip.name
                                                                    }}
                                                                </Link>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center mt-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body flex-row justify-content-center">
                        <AddButton
                            text="Create New Equipment Category"
                            class="mx-auto"
                            @click="categoryFormModal?.show"
                        />
                        <Link
                            v-if="equipmentList.length"
                            :href="$route('equipment.create')"
                            class="mx-auto"
                        >
                            <AddButton text="Create New Equipment" />
                        </Link>
                    </div>
                </div>
            </div>
        </div>
        <Modal
            ref="categoryFormModal"
            :title="modalTitle"
            @show="showForm = true"
            @hidden="showForm = false"
        >
            <CategoryForm
                v-if="showForm"
                :category="activeCategory"
                @success="closeEditModal"
            />
        </Modal>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import EditBadge from "@/Components/_Base/Badges/EditBadge.vue";
import DeleteBadge from "@/Components/_Base/Badges/DeleteBadge.vue";
import Modal from "@/Components/_Base/Modal.vue";
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import CategoryForm from "@/Forms/Equipment/CategoryForm.vue";
import { ref, computed } from "vue";
import verifyModal from "@/Modules/verifyModal";
import { router } from "@inertiajs/vue3";

defineProps<{
    equipmentList: categoryList[];
}>();

const categoryFormModal = ref<InstanceType<typeof Modal> | null>(null);
const showForm = ref<boolean>(false);
const activeCategory = ref<categoryList | null>(null);
const modalTitle = computed(() =>
    activeCategory.value === null ? "Create Category" : "Update Category Name"
);

const openEditModal = (cat: categoryList) => {
    activeCategory.value = cat;
    categoryFormModal.value?.show();
};

const closeEditModal = () => {
    activeCategory.value = null;
    categoryFormModal.value?.hide();
};

const verifyDeleteCategory = (cat: categoryList) => {
    verifyModal("This cannot be undone").then((res) => {
        if (res) {
            console.log("delete");
            router.delete(route("equipment-category.destroy", cat.cat_id));
        }
    });
};
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
