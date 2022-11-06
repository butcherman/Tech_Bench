<template>
    <div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">References</div>
                        <p class="text-center">The <strong>{{ dataType.name }}</strong> Field is assigned to the following Equipment Types:</p>
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <ul class="list-group">
                                    <li v-for="(equip, cat) in equipment" class="list-group-item">
                                        <strong>{{ cat }} Equipment Category</strong>
                                        <ul class="list-group">
                                            <li v-for="item in equip" class="list-group-item">
                                                {{ item.name }}
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
    import App                              from '@/Layouts/app.vue';
    import type { dataListType, equipType } from '@/Types';

    defineProps<{
        dataType: dataListType;
        equipment: {
            [key:string]: equipType[];
        };
    }>();
</script>

<script lang="ts">
    export default { layout: App }
</script>
