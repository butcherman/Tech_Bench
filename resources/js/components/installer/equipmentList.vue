<template>
    <div>
        <div class="row justify-content-center">
            <div class="col-4 grid-margin stretch-card" v-for="cat in equip_list" :key="cat.cat_id">
                <div class="card">
                    <div class="card-header">
                        {{cat.name}}
                    </div>
                    <div class="card-body">
                        <b-list-group>
                            <b-list-group-item v-for="equip in cat.system_types" :key="equip.sys_id">
                                <b-button :href="route('admin.systems.edit', equip.sys_id)" block variant="primary">{{equip.name}}</b-button>
                            </b-list-group-item>
                            <b-list-group-item>
                                <b-button :href="route('admin.systems.show', dashify(cat.name))" block variant="warning">New System for {{cat.name}}</b-button>
                            </b-list-group-item>
                        </b-list-group>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: [
        'equip_list',
    ],
    data() {
        return {


        }
    },
    created()
    {
        // console.log(this.categories);
    },
    methods: {

    }
}
</script>
