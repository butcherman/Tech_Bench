<script setup lang="ts">
import Modal from "@/Components/_Base/Modal.vue";
import TableStacked from "@/Components/_Base/TableStacked.vue";
import verifyModal from "@/Modules/verifyModal";
import { computed, reactive, useTemplateRef } from "vue";
import { Menu } from "primevue";
import { router } from "@inertiajs/vue3";

const props = defineProps<{
    techTip: techTip;
    permissions: techTipPermissions;
}>();

const menuList = useTemplateRef("tech-tip-admin-menu");
const statsModal = useTemplateRef("tech-tip-stats-modal");

/**
 * Management Options for Tech Tip
 */
const managementOptions = computed(() => {
    const options = [];

    if (props.permissions.manage) {
        options.push({
            label: "Show Stats",
            command: () => statsModal.value?.show(),
        });
    }

    if (props.permissions.update) {
        options.push({
            label: "Edit Tip",
            command: () =>
                router.get(route("tech-tips.edit", props.techTip.tip_id)),
        });
    }

    if (props.permissions.delete) {
        options.push({
            label: "Delete Tip",
            command: () =>
                verifyModal("This Tech Tip will no longer be accessible").then(
                    (res) => {
                        if (res) {
                            router.delete(
                                route("tech-tips.destroy", props.techTip.tip_id)
                            );
                        }
                    }
                ),
        });
    }

    return options;
});

/**
 * Tech Tip Statistics
 */
const statsTable = reactive({
    created: props.techTip.created_at,
    created_by: props.techTip.created_by?.full_name,
    updated: props.techTip.updated_by?.full_name
        ? props.techTip.updated_at
        : "",
    updated_by: props.techTip.updated_by?.full_name,
    views: props.techTip.views,
    is_sticky: props.techTip.sticky,
    is_public: props.techTip.public,
});
</script>

<template>
    <div>
        <button
            v-if="managementOptions.length"
            type="button"
            class="px-2"
            v-tooltip="'Tip Management'"
            @click="menuList?.toggle"
        >
            <fa-icon icon="ellipsis-vertical" />
        </button>
        <Menu ref="tech-tip-admin-menu" :model="managementOptions" popup />
        <Modal ref="tech-tip-stats-modal" title="Tech Tip Stats">
            <TableStacked :items="statsTable" />
        </Modal>
    </div>
</template>
