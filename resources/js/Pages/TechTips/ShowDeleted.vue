<template>
    <div>
        <div class="row">
            <div class="col-sm-10 grid-margin">
                <h3>
                    {{tip.subject}}
                </h3>
                <div class="tip-details">
                    <span class="d-block d-sm-inline-block"><strong>ID: </strong>{{tip.tip_id}}</span>
                    <span class="d-block d-sm-inline-block"><strong>Created: </strong>{{tip.created_at}}</span>
                    <span class="d-block d-sm-inline-block"><strong>Last Updated: </strong>{{tip.updated_at}}</span>
                </div>
            </div>
        </div>
        <div class="row justify-content-center mb-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Actions:</div>
                        <div class="text-center">
                            <b-button variant="warning" @click="restoreTip">Restore Tip</b-button>
                            <b-button variant="danger" @click="destroyTip">Permanently Delete Tip</b-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col tip-equipment grid-margin">
                <div><strong>For Equipment:</strong></div>
                <b-badge pill variant="info" class="ml-1 mb-1" v-for="equip in tip.equipment_type" :key="equip.equip_id">{{equip.name}}</b-badge>
            </div>
        </div>
        <div class="row grid-margin justify-content-center">
            <div class="col">
                <div class="card rounded">
                    <div class="card-body">
                        <div class="card-title">Details:</div>
                        <div v-html="tip.details"></div>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="tip.file_uploads.length >= 1" class="row grid-margin justify-content-center">
            <div class="col">
                <div class="card rounded">
                    <div class="card-body">
                        <div class="card-title">Attachments:</div>
                        <ul class="list-group px-5">
                            <li v-for="file in tip.file_uploads" :key="file.file_id" class="list-group-item">
                                <a :href="route('download', [file.file_id, file.file_name])">{{file.file_name}}</a>
                            </li>
                        </ul>
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
             * object from /app/Models/TechTip
             */
            tip: {
                type:     Object,
                required: true,
            }
        },
        methods: {
            /**
             * Remove Soft Deleted timestamp
             */
            restoreTip()
            {
                this.$bvModal.msgBoxConfirm('This will make the Tech Tip accessable again',
                    {
                        title:          'Are You Sure?',
                        size:           'sm',
                        buttonSize:     'sm',
                        okVariant:      'danger',
                        okTitle:        'YES',
                        cancelTitle:    'NO',
                        footerClass:    'p-2',
                        hideHeaderClose: false,
                        centered:        true
                    }).then(value => {
                        if(value)
                        {
                            this.$inertia.get(route('admin.tips.restore', this.tip.tip_id));
                        }
                    });
            },
            /**
             * Tip and all associated files will be destroyed
             * Cannot undo this operation
             */
            destroyTip()
            {
                this.$bvModal.msgBoxConfirm('This will remove the Tech Tip and all associated files',
                    {
                        title:          'Are You Sure?',
                        size:           'sm',
                        buttonSize:     'sm',
                        okVariant:      'danger',
                        okTitle:        'YES',
                        cancelTitle:    'NO',
                        footerClass:    'p-2',
                        hideHeaderClose: false,
                        centered:        true
                    }).then(value => {
                        if(value)
                        {
                            this.$inertia.delete(route('admin.tips.force-delete', this.tip.tip_id));
                        }
                    });
            }
        },
        metaInfo: {
            title: 'Deleted Tech Tip',
        }
    }
</script>
