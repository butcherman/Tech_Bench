<template>
    <div>
        <div class="row grid-margin">
            <div class="col-12">
                <h4 class="text-center text-md-left">Flagged Tech Tip Comments</h4>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-10 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">The Following Comments Have Been Flagged</div>
                        <b-table responsive :fields="fields" :items="flagged" striped empty-text="No Flagged Comments" show-empty>
                            <template #cell(tip_id)="data">
                                <inertia-link :href="route('tech-tips.show', data.item.tip_id)" title="Click to See Full Tip" v-b-tooltip.hover>{{data.item.tip_id}}</inertia-link>
                            </template>
                            <template #cell(actions)="data">
                                <i class="fas fa-ban pointer" title="Remove Flag" v-b-tooltip.hover @click="removeFlag(data.item)"></i>
                                <i class="far fa-trash-alt pointer" title="Delete Comment" v-b-tooltip.hover @click="deleteComment(data.item)"></i>
                            </template>
                        </b-table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import App from '../../../Layouts/app';

    export default {
        layout: App,
        props: {
            /**
             * Array of objects from /app/Models/TechTipComment where the 'flagged' column is set to true
             */
            flagged: {
                type:     Array,
                required: true,
            }
        },
        data() {
            return {
                fields: [
                    {
                        key:     'tip_id',
                        label:   'Tech Tip',
                        sortable: true,
                    },
                    {
                        key:     'comment',
                        label:   'Comment',
                        sortable: true,
                    },
                    {
                        key:     'user.full_name',
                        label:   'Comment By',
                        sortable: true,
                    },
                    {
                        key:     'actions',
                        label:   'Actions',
                        sortable: false,
                    },
                ]
            }
        },
        methods: {
            /**
             * Set 'flagged' column to false
             */
            removeFlag(comment)
            {
                this.$inertia.get(route('tips.comments.show', comment.id));
            },
            /**
             * Delete the comment from the database
             */
            deleteComment(comment)
            {
                this.$inertia.delete(route('tips.comments.destroy', comment.id));
            }
        }
    }
</script>
