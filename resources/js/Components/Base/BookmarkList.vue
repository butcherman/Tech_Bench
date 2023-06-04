<template>
    <h5 class="text-center">{{ upperFirst(type) }}</h5>
    <ul class="list-group">
        <li
            v-for="item in list"
            :key="item.id"
            class="list-group-item"
        >
            <Link
                as="button"
                :href="item.link"
                class="btn btn-info btn-pill w-100"
            >
                {{ item.name }}
            </Link>
        </li>
    </ul>
</template>

<script setup lang="ts">
import { ref, onMounted } from "vue";
import { upperFirst } from "lodash";

interface list {
    id: number;
    name: string;
    link: string;
}

const props = defineProps<{
    type: "customers" | "tip";
    list: customer[] | techTip[];
}>();

const list = ref<list[]>([]);

const buildItemList = () => {
    props.list.forEach((item: customer | techTip) => {
        console.log(item);

        list.value.push({
            id: item.cust_id,
            name: item.name,
            link: route('customers.show', item.slug),
        });
    })
}
onMounted(() => buildItemList());

</script>
