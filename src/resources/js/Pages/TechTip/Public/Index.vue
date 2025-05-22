<script setup lang="ts">
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import Card from "@/Components/_Base/Card.vue";
import KbLayout from "@/Layouts/KnowledgeBase/KbLayout.vue";
import SearchTipsForm from "@/Forms/TechTip/SearchTipsForm.vue";
import SearchFilters from "@/Components/TechTips/Search/SearchFilters.vue";
import TipsSearchTable from "@/Components/TechTips/Search/TipsSearchTable.vue";
import { ref, onMounted } from "vue";
import { triggerPublicSearch } from "@/Composables/TechTip/TipSearch.module";

defineProps<{
    filterData: {
        tip_types: tipType[];
        equip_types: equipmentCategory[];
    };
}>();

const filterHidden = ref<boolean>(true);

onMounted(() => triggerPublicSearch());
</script>

<script lang="ts">
export default { layout: KbLayout };
</script>

<template>
    <div>
        <Card class="tb-card-lg mb-4">
            <SearchTipsForm
                placeholder="What would you like to search for?"
                is-public
            />
        </Card>
        <div class="flex flex-col md:flex-row gap-3">
            <Card class="basis-1/4" title="Search Filters">
                <template #append-title>
                    <BaseButton
                        class="my-2 mx-2 md:hidden"
                        icon="bars"
                        variant="light"
                        @click="filterHidden = !filterHidden"
                    />
                </template>
                <SearchFilters
                    :filter-data="filterData"
                    :class="{ 'filter-hidden': filterHidden }"
                    class="filter"
                    is-public
                />
            </Card>
            <Card class="grow">
                <TipsSearchTable is-public />
            </Card>
        </div>
    </div>
</template>

<style scoped lang="postcss">
.filter {
    transition: height;
    transition-duration: 0.5s;
}

.filter-hidden {
    @apply -md:h-0;
}
</style>
