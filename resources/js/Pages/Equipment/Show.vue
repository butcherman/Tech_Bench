<template>
    <div>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Customers with this equipment assigned</div>
                        <div v-if="customers.length === 0" class="text-center">No Customer References</div>
                        <ul v-else class="list-group">
                            <li v-for="cust in customers" class="list-group-item">
                                <Link :href="$route('customers.show', cust.slug)">{{ cust.name }}</Link>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Tech Tips with this equipment assigned</div>
                        <div v-if="techTip.length === 0" class="text-center">No Tech Tip References</div>
                        <ul v-else class="list-group">
                            <li v-for="tip in techTip" class="list-group-item">
                                <Link :href="$route('tech-tips.show', tip.slug)">{{ tip.subject }}</Link>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
    import App from '@/Layouts/app.vue';
    import { customerType, equipType, techTipWithEquipment } from '@/Types';
    import { ref, reactive, onMounted } from 'vue';

    const $route = route;
    const props = defineProps<{
        equipment: equipType;
        customers: customerType[];
        techTip  : techTipWithEquipment[];
    }>();
</script>

<script lang="ts">
    export default { layout: App }
</script>
