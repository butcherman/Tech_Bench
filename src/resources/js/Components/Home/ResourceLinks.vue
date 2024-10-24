<template>
    <div class="row">
        <div class="col">
            <h5 class="text-center">Customers</h5>
            <ul class="list-group">
                <li
                    v-if="!customers.length"
                    class="list-group-item text-center"
                >
                    Nothing to see here...
                </li>
                <li
                    v-for="cust in customers"
                    :key="cust.slug"
                    class="list-group-item quick-link text-center"
                >
                    <Link :href="$route('customers.show', cust.slug)">
                        {{ cust.name }}
                    </Link>
                </li>
            </ul>
        </div>
        <div class="col">
            <h5 class="text-center">Tech Tips</h5>
            <ul class="list-group">
                <li v-if="!techTips.length" class="list-group-item text-center">
                    Nothing to see here...
                </li>
                <li
                    v-for="tip in techTips"
                    class="list-group-item quick-link text-center"
                    :key="tip.slug"
                >
                    <Link :href="$route('tech-tips.show', tip.slug)">
                        {{ tip.subject }}
                    </Link>
                </li>
            </ul>
        </div>
    </div>
</template>

<script setup lang="ts">
defineProps<{
    techTips: techTip[];
    customers: customer[];
}>();
</script>

<style scoped lang="scss">
.quick-link {
    padding: 0;
    a {
        color: #000000;
        display: block;
        padding: 0.5rem;
        text-decoration: none;

        &:hover {
            background-color: #b1e0e9;
        }
    }
}
</style>
