<template>
    <div>
        <button
            class="btn btn-light mb-1 collapsed"
            type="button"
            data-bs-toggle="collapse"
            :data-bs-target="`#${collapseId}`"
        >
            <span class="collapsed-text">
                {{ buttonText || collapsedText || "More Information" }}
            </span>
            <span class="expanded-text">
                {{ buttonText || expandedText || "Less Information" }}
            </span>
            <fa-icon icon="caret-down" class="collapse-icon ms-1" />
        </button>
        <div :id="collapseId" class="collapse border p-1"><slot /></div>
    </div>
</template>

<script setup lang="ts">
import { v4 as uuidv4 } from "uuid";

defineProps<{
    buttonText?: string;
    expandedText?: string;
    collapsedText?: string;
}>();

const collapseId = uuidv4();
</script>

<style scoped lang="scss">
.collapsed-text {
    display: none;
}
.expanded-text {
    display: inline;
}

.collapsed {
    .collapse-icon {
        transform: rotate(270deg);
    }
    .collapsed-text {
        display: inline;
    }
    .expanded-text {
        display: none;
    }
}
</style>
