<template>
    <div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <Card
                v-for="cat in equipmentList"
                :title="cat.name"
                :key="cat.cat_id"
            >
                <template #append-title>
                    <EditBadge
                        class="mx-1 pointer"
                        v-tooltip="`Edit ${cat.name}`"
                        @click="editCategory(cat)"
                    />
                    <DeleteBadge
                        v-if="!cat.equipment_type.length"
                        v-tooltip="`Delete ${cat.name}`"
                        confirm
                        @accepted="deleteCategory(cat)"
                    />
                </template>
                <ResourceList
                    :list="cat.equipment_type"
                    empty-text="No Equipment"
                    label-field="name"
                    :link-fn="(item) => $route('equipment.edit', item.equip_id)"
                />
            </Card>
        </div>
        <div class="flex place-content-center mt-3 gap-3">
            <AddButton text="Create New Category" @click="addCategory" />
            <AddButton
                text="Create New Equipment"
                :href="$route('equipment.create')"
            />
        </div>
        <Modal ref="category-modal" @hidden="clearModal">
            <CategoryForm
                v-if="modalShown"
                :category="activeCategory"
                @success="categoryModal?.hide()"
            />
        </Modal>
    </div>
</template>

<script setup lang="ts">
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import CategoryForm from "@/Forms/Equipment/CategoryForm.vue";
import DeleteBadge from "@/Components/_Base/Badges/DeleteBadge.vue";
import EditBadge from "@/Components/_Base/Badges/EditBadge.vue";
import Modal from "@/Components/_Base/Modal.vue";
import ResourceList from "@/Components/_Base/ResourceList.vue";
import { ref, useTemplateRef } from "vue";
import { router } from "@inertiajs/vue3";

defineProps<{
    equipmentList: categoryList[];
}>();

const categoryModal = useTemplateRef("category-modal");
const modalShown = ref<boolean>(false);
const activeCategory = ref<categoryList | undefined>(undefined);

/**
 * Create a new category
 */
const addCategory = (): void => {
    modalShown.value = true;
    categoryModal.value?.show();
};

/**
 * Edit a Category name
 */
const editCategory = (cat: categoryList): void => {
    activeCategory.value = cat;
    modalShown.value = true;
    categoryModal.value?.show();
};

/**
 * Delete a category after confirmation
 */
const deleteCategory = (cat: categoryList): void => {
    router.delete(route("equipment-category.destroy", cat.cat_id));
};

/**
 * Reset Category Form
 */
const clearModal = (): void => {
    activeCategory.value = undefined;
    modalShown.value = false;
};
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
