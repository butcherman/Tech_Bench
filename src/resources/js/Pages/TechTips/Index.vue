<template>
    <div>
        <Head title="Tech Tips" />
        <div v-if="canCreate" class="row justify-content-center my-2">
            <div class="col">
                <Link :href="$route('tech-tips.create')">
                    <AddButton class="float-end" small pill>
                        Add New Tech Tip
                    </AddButton>
                </Link>
            </div>
        </div>
        <div class="row my-4">
            <div class="col">
                <div class="card">
                    <div class="card-body"><TipSearchForm /></div>
                </div>
            </div>
        </div>
        <div class="row my-4">
            <div class="col-lg-3 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">
                            <button
                                class="btn d-inline d-md-none"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#filter-list"
                                title="Show Filters"
                                v-tooltip
                            >
                                <fa-icon icon="bars" />
                            </button>
                            Search Filters
                        </div>
                        <div class="collapse d-md-block" id="filter-list">
                            <TipSearchFilters
                                :tip-types="filterData.tip_types"
                                :equip-types="filterData.equip_types"
                            />
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-12">
                <div class="card">
                    <div class="card-body">
                        {{ searchResults }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import TipSearchForm from "@/Forms/TechTips/TipSearchForm.vue";
import TipSearchFilters from "@/Forms/TechTips/TipSearchFilters.vue";
import { ref, reactive, onMounted } from "vue";
import { searchResults } from "@/Modules/TipSearch.module";

const props = defineProps<{
    canCreate: boolean;
    filterData: {
        tip_types: tipType[];
        equip_types: categoryList[];
    };
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
