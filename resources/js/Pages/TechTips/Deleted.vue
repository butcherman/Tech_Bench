<template>
    <div>
        <div class="row grid-margin">
            <div class="col-md-12">
                <h4 class="text-center text-md-left">Deleted Tech Tips</h4>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-9 col-12 stretch-card">
                <div class="card">
                    <div class="card-body">
                        <b-table :items="list" :fields="fields" striped responsive :busy="loading" show-empty>
                            <template #empty>
                                <h5 class="text-center">Nothing to See Here</h5>
                                <p class="text-center">No Deleted Tech Tips</p>
                            </template>
                            <template #table-busy>
                                <atom-loader></atom-loader>
                            </template>
                            <template #cell(subject)="data">
                                <inertia-link :href="route('tips.show-deleted', data.item.slug)">{{data.item.subject}}</inertia-link>
                            </template>
                            <template #cell(preview)="data">
                                <i class="pointer fas" :class="data.detailsShowing ? 'fa-eye-slash' : 'fa-eye'" :title="data.detailsShowing ? 'Hide Preview' : 'Show Preview'" v-b-tooltip.hover @click="data.toggleDetails"></i>
                            </template>
                            <template #cell(pinned)="data">
                                <i v-if="data.item.sticky" class="fas fa-thumbtack text-danger" title="Pinned Tip" v-b-tooltip.hover></i>
                            </template>
                            <template #row-details="data">
                                <div v-html="data.item.summary"></div>
                                <div>
                                    <strong>For Equipment:</strong>
                                    <b-badge pill variant="primary" v-for="equip in data.item.equipment_type" :key="equip.equip_id">{{equip.name}}</b-badge>
                                </div>
                            </template>
                        </b-table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import App from '../../Layouts/app';

    export default {
        layout: App,
        props: {
            /**
             * Array of Objects - Soft Deleted Tech Tips from /app/Models/TechTip
             */
            list: {
                type:     Array,
                required: true,
            }
        },
        data() {
            return {
                loading: false,
                fields: [
                    {
                        key:     'pinned',
                        label:   '',
                        sortable: false,
                    },
                    {
                        key:     'preview',
                        label:   '',
                        sortable: false,
                    },
                    {
                        key:     'subject',
                        label:   'Subject',
                        sortable: true,
                    },
                    {
                        key:     'deleted_at',
                        label:   'Date Deleted',
                        sortable: true,
                    },
                ]
            }
        },
    }
</script>
