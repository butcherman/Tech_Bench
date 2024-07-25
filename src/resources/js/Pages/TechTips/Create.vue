<template>
    <div>
        <Head title="Create New Tech Tip" />
        <div class="row justify-content-center">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <TechTipForm
                            :tip-types="tipTypes"
                            :equip-list="equipStore.getMultiSelectBox()"
                            @success="handleSuccess"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import TechTipForm from "@/Forms/TechTips/TechTipForm.vue";
import { ref, reactive, onMounted } from "vue";
import { router } from "@inertiajs/vue3";
import { useEquipmentStore } from "@/Store/EquipmentStore";

const props = defineProps<{
    tipTypes: tipType[];
    // equipList: categoryList[];
}>();

const equipStore = useEquipmentStore();

const handleSuccess = (newSlug: string) => {
    console.log(newSlug);
    router.get(route("tech-tips.show", newSlug));
};
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
