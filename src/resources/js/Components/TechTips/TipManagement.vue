<template>
    <div id="manage-tip-dropdown" class="dropdown">
        <button
            class="btn rounded-circle dropdown-toggle"
            type="button"
            data-bs-toggle="dropdown"
        >
            <fa-icon icon="ellipsis-vertical" />
        </button>
        <ul class="dropdown-menu">
            <li v-if="permissions.manage">
                <span class="dropdown-item pointer" @click="statsModal?.show">
                    Show Stats
                </span>
            </li>
            <li v-if="permissions.update">
                <Link
                    :href="$route('tech-tips.edit', tipData.slug)"
                    class="dropdown-item"
                >
                    Edit Tip
                </Link>
            </li>
            <li v-if="permissions.delete">
                <span class="dropdown-item pointer" @click="confirmDelete">
                    Delete Tip
                </span>
            </li>
        </ul>
        <Modal ref="statsModal" title="Tech Tip Stats">
            <TableStacked :rows="statsTable" title-case />
        </Modal>
    </div>
</template>

<script setup lang="ts">
import verifyModal from "@/Modules/verifyModal";
import Modal from "../_Base/Modal.vue";
import TableStacked from "../_Base/TableStacked.vue";
import { ref, reactive } from "vue";
import { router } from "@inertiajs/vue3";

const props = defineProps<{
    tipData: techTip;
    permissions: techTipPermissions;
}>();

const statsModal = ref<InstanceType<typeof Modal> | null>(null);
const statsTable = reactive({
    author: props.tipData.created_by.full_name,
    created_at: props.tipData.created_at,
    updated_at: props.tipData.updated_id ? props.tipData.updated_at : "N/A",
    updated_by: props.tipData.updated_id
        ? props.tipData.updated_by.full_name
        : "N/A",
    views: props.tipData.tech_tip_view.views,
    is_sticky: props.tipData.sticky,
    is_public: props.tipData.public,
});

const confirmDelete = () => {
    verifyModal("Do you want to delete this Tech Tip?").then((res) => {
        if (res) {
            router.delete(route("tech-tips.destroy", props.tipData.slug));
        }
    });
};
</script>
