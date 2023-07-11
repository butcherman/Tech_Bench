<template>
    <div>
        <Head title="Equipment Administration" />
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Equipment Administration</div>
                        <div v-if="!equipList.length" class="text-center">
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
                                        v-for="cat in equipList"
                                        :key="cat.cat_id"
                                    >
                                        <tr>
                                            <th>{{ cat.name }}</th>
                                            <td class="text-end">
                                                <EditBadge
                                                    tooltip="Edit Category Name"
                                                    @click="editCategory(cat)"
                                                />
                                                <DeleteBadge
                                                    v-if="
                                                        !cat.equipment_type
                                                            .length
                                                    "
                                                    tooltip="Delete Category"
                                                    @click="deleteCategory(cat)"
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
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body text-center">
                        <button
                            class="btn btn-info w-75 my-2"
                            @click="createCategory"
                        >
                            Create New Equipment Category
                        </button>
                        <Link
                            v-if="equipList.length"
                            :href="$route('equipment.create')"
                            class="btn btn-info w-75 my-2"
                        >
                            Create New Equipment
                        </Link>
                    </div>
                </div>
            </div>
        </div>
        <Modal ref="categoryFormModal">
            <CategoryForm
                ref="categoryForm"
                @success="categoryFormModal?.hide"
            />
        </Modal>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import EditBadge from "@/Components/_Base/Badges/EditBadge.vue";
import DeleteBadge from "@/Components/_Base/Badges/DeleteBadge.vue";
import CategoryForm from "@/Forms/Equipment/CategoryForm.vue";
import Modal from "@/Components/_Base/Modal.vue";
import verify from "@/Modules/verify";
import { ref } from "vue";
import { router } from "@inertiajs/vue3";

defineProps<{
    equipList: categoryList[];
}>();

const categoryFormModal = ref<InstanceType<typeof Modal> | null>(null);
const categoryForm = ref<InstanceType<typeof CategoryForm> | null>(null);

const createCategory = () => {
    categoryForm.value?.createNew();
    categoryFormModal.value?.show();
};

const editCategory = (category: categoryList) => {
    categoryForm.value?.editCat(category);
    categoryFormModal.value?.show();
};

const deleteCategory = (category: categoryList) => {
    verify().then((res) => {
        if (res) {
            router.delete(route("equipment-category.destroy", category.cat_id));
        }
    });
};
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
