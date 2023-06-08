<template>
    <div>
        <p class="text-center">{{ msg }}</p>
        <p class="text-center">
            <Link
                as="button"
                :href="$route('customers.show', slug)"
                class="btn btn-info"
                @click="$emit('hideNotification')"
            >
                Click Here to Go To {{ customer }}
            </Link>
        </p>
    </div>
</template>

<script setup lang="ts">
import { computed } from "vue";

const $route = route;
const props = defineProps<{
    new: boolean;
    customer: string;
    slug: string;
    equipment: string;
}>();
defineEmits(['hideNotification']);

const msg = computed(() => {
    if (props.new) {
        return `${props.equipment} has just been added to ${props.customer}`;
    }

    return `Equipment Data for ${props.equipment} has been updated for ${props.customer}`;
});
</script>
