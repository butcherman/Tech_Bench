<template>
    <div>
        <span class="mask-text">
            <fa-icon icon="ellipsis" />
            <fa-icon icon="ellipsis" />
            <fa-icon icon="ellipsis" />
            <fa-icon icon="ellipsis" />
            <fa-icon icon="ellipsis" />
        </span>
        <span class="mask-value">
            <a
                v-if="data.data_field_type.is_hyperlink"
                :href="checkForHyperlink(data.value)"
                target="_blank"
                >{{ data.value }}</a
            >
            <span v-else style="-webkit-text-security: disk">
                {{ data.value }}
            </span>
        </span>
    </div>
</template>

<script setup lang="ts">
defineProps<{
    data: customerEquipmentData;
}>();

/**
 * If a Hyperlink field does not have http or https in front, we will add https
 */
const checkForHyperlink = (url: string): string => {
    if (!url.match("^(http|https)://")) {
        return "https://" + url;
    }

    return url;
};
</script>
