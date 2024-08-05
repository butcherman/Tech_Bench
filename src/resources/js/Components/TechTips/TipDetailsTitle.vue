<template>
    <div>
        <h3>{{ tipData.subject }}</h3>
        <div class="tip-details">
            <a
                v-if="!publicShown"
                :href="$route('tech-tips.download', tipData.slug)"
                class="btn btn-info btn-sm float-end mb-2"
                title="Download Tech Tip"
                target="_blank"
                v-tooltip
            >
                <fa-icon icon="download" />
            </a>
            <span class="d-block d-sm-inline-block">
                <strong>ID: </strong>
                {{ tipData.tip_id }}
            </span>
            <span class="d-block d-sm-inline-block">
                <strong>Created: </strong>
                {{ tipData.created_at }}
            </span>
            <span v-if="tipData.updated_id" class="d-block d-sm-inline-block">
                <strong>Last Updated: </strong>
                {{ tipData.updated_at }}
            </span>
            <span
                v-if="tipData.public && !publicShown"
                class="d-block d-sm-inline-block"
            >
                <fa-icon icon="star" class="text-success" />
                <strong>Public Tech Tip </strong>
            </span>
        </div>
    </div>
</template>

<script setup lang="ts">
defineProps<{
    tipData: techTip;
    publicShown?: boolean;
}>();
</script>

<style scoped lang="scss">
.tip-details {
    span {
        padding: 0 5px;
        border-right: 1px solid #999999;
        color: #999999;
        display: inline-block;
        &:last-child {
            border-right: none;
        }
    }
}
</style>
