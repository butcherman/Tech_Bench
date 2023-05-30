<template>
    <Head title="Equipment" />
    <div>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Equipment Categories</div>
                        <ul class="list-group">
                            <li
                                v-for="cat in catList"
                                class="list-group-item text-center pointer list-group-item-action"
                                @click="populateTypes(cat)"
                            >
                                {{ cat.name }}
                                <Link
                                    :href="
                                        $route(
                                            'equipment_categories.edit',
                                            cat.cat_id
                                        )
                                    "
                                    class="float-end text-warning"
                                    :title="`Edit ${cat.name}`"
                                    v-tooltip
                                >
                                    <fa-icon icon="fa-edit" />
                                </Link>
                            </li>
                            <li class="list-group-item text-center">
                                <Link
                                    :href="
                                        $route('equipment_categories.create')
                                    "
                                    class="btn btn-primary w-50"
                                >
                                    Add Category
                                </Link>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Equipment Types</div>
                        <Transition name="fade" mode="out-in">
                            <p v-if="equipTypes === null" class="text-center">
                                Select a Category to see equipment types
                            </p>
                            <div
                                v-else-if="equipTypes.length === 0"
                                class="text-center"
                            >
                                No equipment has been created for this Category
                                <p class="mt-2">
                                    <Link
                                        :href="
                                            $route(
                                                'equipment.create',
                                                activeCatName
                                            )
                                        "
                                        class="btn btn-primary w-75 m-2"
                                    >
                                        Add Equipment Type
                                    </Link>
                                    <button
                                        method="delete"
                                        class="btn btn-danger w-75 m-2"
                                        @click="verifyDeleteCat"
                                    >
                                        Delete Category
                                    </button>
                                </p>
                            </div>
                            <ul
                                v-else
                                class="list-group"
                                :key="equipTypes[0]?.cat_id"
                            >
                                <li
                                    v-for="equip in equipTypes"
                                    :key="equip?.equip_id"
                                    class="list-group-item text-center list-group-item-action"
                                >
                                    {{ equip?.name }}
                                    <Link
                                        :href="
                                            $route(
                                                'equipment.edit',
                                                equip?.equip_id
                                            )
                                        "
                                        class="float-end text-warning mx-1"
                                        :title="`Edit ${equip?.name}`"
                                        v-tooltip
                                    >
                                        <fa-icon icon="fa-edit" />
                                    </Link>
                                    <!-- <Link      TODO - Add References
                                        href="#"
                                        class="float-end text-primary mx-1"
                                        title="References"
                                        v-tooltip
                                    >
                                        <fa-icon icon="fa-asterisk" />
                                    </Link> -->
                                </li>
                                <li
                                    class="list-group-item text-center"
                                    key="whatsLeft"
                                >
                                    <Link
                                        :href="
                                            $route(
                                                'equipment.create',
                                                activeCatName
                                            )
                                        "
                                        class="btn btn-primary w-50"
                                    >
                                        Add Equipment Type
                                    </Link>
                                </li>
                            </ul>
                        </Transition>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import App from "@/Layouts/app.vue";
import { ref } from "vue";
import { verifyModal } from "@/Modules/verifyModal.module";
import { router } from "@inertiajs/vue3";

defineProps<{
    catList: categoryList[];
}>();

const $route = route;
const activeCatId = ref<number>();
const activeCatName = ref<string>();
const equipTypes = ref<equipment[] | null[] | null>(null);

const populateTypes = (category: categoryList) => {
    activeCatId.value = category.cat_id;
    activeCatName.value = category.name;
    equipTypes.value = category.equipment_type.length
        ? category.equipment_type
        : [];
};

const verifyDeleteCat = () => {
    verifyModal("This cannot be undone").then((res) => {
        if (res) {
            router.delete(
                route("equipment_categories.destroy", activeCatId.value),
                {
                    onFinish: () => (equipTypes.value = null),
                }
            );
        }
    });
};
</script>

<script lang="ts">
export default { layout: App };
</script>
