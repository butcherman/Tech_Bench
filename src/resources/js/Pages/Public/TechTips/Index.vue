<template>
    <div>
        <h2 class="text-center">End User Knowledge Base</h2>
        <div class="row my-4">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <TechTipSearchForm />
                    </div>
                </div>
            </div>
        </div>
        <div class="row my-4">
            <div class="col-md-3 col-12">
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
                            <TipSearchFilters :equip-types="equipTypes" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9 col-12">
                <div class="card">
                    <div class="card-body">
                        <Overlay :loading="isLoading">
                            <TipSearchBody />
                        </Overlay>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import KbLayout from "@/Layouts/KbLayout.vue";
import Overlay from "@/Components/_Base/Loaders/Overlay.vue";
import TechTipSearchForm from "@/Forms/Public/TechTips/TechTipSearchForm.vue";
import TipSearchFilters from "@/Forms/Public/TechTips/TipSearchFilters.vue";
import TipSearchBody from "@/Components/TechTips/TipSearchBody.vue";
import { triggerPublicSearch, isLoading } from "@/Modules/TipSearch.module";
import { ref, reactive, onMounted } from "vue";

const props = defineProps<{
    equipTypes: categoryList[];
}>();

onMounted(() => triggerPublicSearch());
</script>

<script lang="ts">
export default { layout: KbLayout };
</script>
