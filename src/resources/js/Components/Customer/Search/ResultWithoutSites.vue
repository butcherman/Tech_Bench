<script setup lang="ts">
defineProps<{
    cust: customer;
}>();
</script>

<template>
    <td colspan="2">
        <Link
            :href="$route('customers.show', cust.slug)"
            class="m-0 p-2 block hover:font-semi-bold"
        >
            {{ cust.name }}
        </Link>
    </td>
    <td class="border-s">
        <Link
            :href="$route('customers.show', cust.slug)"
            class="m-0 p-2 block hover:font-semi-bold"
        >
            {{ cust.sites[0].city }}
        </Link>
    </td>
</template>
