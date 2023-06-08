<template>
    <div>
        <p class="text-center">{{ msg }}</p>
        <p class="text-center">
            <Link
                as="button"
                :href="$route('customers.show', customer.slug)"
                class="btn btn-info"
                @click="$emit('hideNotification')"
            >
                Click Here to Go To {{ customer.name }}
            </Link>
        </p>
    </div>
</template>

<script setup lang="ts">
import { computed } from "vue";

const $route = route;
const props = defineProps<{
    new: boolean;
    customer: customer;
    contact: customerContact;
}>();

defineEmits(["hideNotification"]);

const msg = computed(() => {
    if (props.new) {
        return `${props.contact.name} was just created as a contact for ${props.customer.name}`;
    }

    return `Customer Contact ${props.contact.name} was just updated for ${props.customer.name}`;
});
</script>
