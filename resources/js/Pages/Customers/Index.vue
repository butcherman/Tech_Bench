<template>
    <div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <div class="clearfix mb-2">
                                <Link
                                    as="button"
                                    :href="route('customers.create')"
                                    class="btn btn-pill btn-info float-end"
                                >
                                    <fa-icon icon="plus" />
                                    Add New Customer
                                </Link>
                            </div>
                            <div>

                            </div>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Customer Name</th>
                                        <th>City</th>
                                        <th>Equipment</th>
                                    </tr>
                                    <!-- <tr>
                                        <td><TextInput id="name" name="name" label="Search Name or ID" /></td>
                                        <td><TextInput id="city" name="city" label="Search City" /></td>
                                        <td><SelectOptionGroup id="equipment" name="equip" :option-list="equipment" /></td>
                                    </tr> -->
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
    import App from '@/Layouts/app.vue';
    import TextInput from '@/Components/Base/Input/TextInput.vue';
    import SelectOptionGroup from '@/Components/Base/Input/SelectOptionGroup.vue';
    import { ref, reactive, onMounted } from 'vue';
import VueForm from '@/Components/Base/VueForm.vue';

    const props = defineProps<{
        permissions: string[];
        equipment: any;
    }>();

    const onSearch = (form) => {
        console.log(form);
    }
</script>

<script lang="ts">
    export default { layout: App, components: { VueForm } }
</script>

<style scoped lang="scss">
    thead {
        tr {
            background-color: rgb(233, 225, 225);
        }
    }
</style>
