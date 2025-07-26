import EquipmentData from
'@/Components/Customer/Show/Equipment/EquipmentData.vue';
<script setup lang="ts">
import BaseBadge from "@/Components/_Base/Badges/BaseBadge.vue";
import EditBadge from "@/Components/_Base/Badges/EditBadge.vue";
import ClipboardCopy from "@/Components/_Base/ClipboardCopy.vue";
import { permissions } from "@/Composables/Customer/CustomerData.module";
import { ref } from "vue";

defineEmits<{
    editField: [customerEquipmentData];
}>();

defineProps<{
    data: customerEquipmentData;
}>();

const isMasked = ref<boolean>(true);

/**
 * If a Hyperlink field does not have http or https in front, we will add https
 */
const checkForHyperlink = (url: string): string => {
    if (!url.match("^(http|https)://")) {
        return `https://${url}`;
    }

    return url;
};
</script>

<template>
    <div class="flex flex-row">
        <div class="grow">
            <div
                :class="{
                    'mask-field': data.data_field_type.masked && isMasked,
                }"
            >
                <a
                    v-if="data.value && data.data_field_type.is_hyperlink"
                    :href="checkForHyperlink(data.value)"
                    target="_blank"
                >
                    {{ data.value }}
                </a>
                <span v-else>
                    {{ data.value }}
                </span>
            </div>
        </div>
        <div>
            <BaseBadge
                v-if="data.data_field_type.masked"
                :icon="isMasked ? 'eye' : 'eye-slash'"
                class="mx-1"
                v-tooltip.left="isMasked ? 'Show Value' : 'Hide Value'"
                @click="isMasked = !isMasked"
            />
            <ClipboardCopy
                v-if="data.value && data.data_field_type.allow_copy"
                class="mx-1"
                :value="data.value"
            />
            <EditBadge
                v-if="permissions.equipment.update"
                @click="$emit('editField', data)"
            />
        </div>
    </div>
</template>
