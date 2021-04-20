<template>
    <div>
        <div class="card-title">
            Equipment:
            <new-equipment-modal :existing_equip="equipIdList" :cust_id="cust_id" @completed="getEquipment"></new-equipment-modal>
        </div>
        <b-overlay :show="loading">
            <div v-if="equipment.length > 0">
                <div v-for="(equip, index) in equipment" :key="index" >
                    <b-card-header >
                        <b-button block variant="info" v-b-toggle="'equip-'+index">{{equip.name}}</b-button>
                    </b-card-header>
                    <b-collapse :id="'equip-'+index" accordion="equipment-accordion" :visible="index === 0 ? true : false">
                        <b-card-body>
                            <b-table stacked small :items="getEquipData(equip.customer_equipment_data)"></b-table>
                            <div class="text-center">
                                <b-button variant="warning" pill size="sm">
                                    <i class="fas fa-pencil-alt"></i>
                                    Update
                                </b-button>
                            </div>
                        </b-card-body>
                    </b-collapse>
                </div>
            </div>
            <div v-else>
                <h5 class="text-center">No Equipment Has Been Assigned</h5>
            </div>
        </b-overlay>
    </div>
</template>

<script>
    import newEquipmentModal from './Equipment/newEquipmentModal.vue';

    export default {
        components: { newEquipmentModal },
        props: {
            customer_equipment: {
                type:     Array,
                required: true,
            },
            cust_id: {
                type:     Number,
                required: true,
            }
        },
        data() {
            return {
                equipment: this.customer_equipment,
                loading:   false,
            }
        },
        created() {
            //
        },
        mounted() {
            //
            // console.log(this.equipIdList);
        },
        computed: {
            equipIdList()
            {
                var list = [];
                this.equipment.forEach(function(item)
                {
                    list.push(item.equip_id);
                });

                return list;
            }
        },
        watch: {
            //
        },
        methods: {
            getEquipData(data)
            {
                var dataList  = [];
                data.forEach(el => {
                    dataList[el.field_name] = el.value;
                });

                return [dataList];
            },
            getEquipment()
            {
                this.loading = true;
                axios.get(this.route('customers.equipment.show', this.cust_id))
                    .then(res => {
                        console.log(res);
                        this.equipment = res.data;
                        this.loading = false;
                    }).catch(error => this.eventHub.$emit('axiosError', error));
            }
        },
    }
</script>
