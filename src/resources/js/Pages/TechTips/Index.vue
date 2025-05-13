<script setup lang="ts">
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import AppLayout from "@/Layouts/App/AppLayout.vue";
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import Card from "@/Components/_Base/Card.vue";
import SearchFilters from "@/Components/TechTips/Search/SearchFilters.vue";
import SearchTipsForm from "@/Forms/TechTip/SearchTipsForm.vue";
import { ref } from "vue";

defineProps<{
    permissions: techTipPermissions;
    filterData: {
        tip_types: tipType[];
        equip_types: equipmentCategory[];
    };
}>();

const filterHidden = ref<boolean>(true);
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div>
        <div
            v-if="permissions.create"
            class="w-full flex flex-row-reverse tb-gap-y"
        >
            <AddButton
                text="New Tech Tip"
                :href="$route('tech-tips.create')"
                pill
            />
        </div>
        <Card class="tb-gap-y">
            <SearchTipsForm />
        </Card>
        <div class="flex flex-col md:flex-row gap-3">
            <Card class="basis-1/3" title="Search Filters">
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
                />
            </Card>
            <Card class="grow"> Results </Card>
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
