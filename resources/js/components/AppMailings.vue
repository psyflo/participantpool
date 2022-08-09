<template>
    <div class="app-mailings">
        <h3>{{ $t('components.mailings.title') }}</h3>

        <div v-if="$app.security_authenticated && $app.security_can({ context: $app.security.context.MAILING, ability: $app.security.ability.READ })">

            <div>
                <b-button variant="link" v-on:click="loadData">{{ $t('components.mailings.actions.refresh') }}</b-button>
            </div>

            <b-table v-bind:items="filteredData" v-bind:fields="columns" responsive="sm" v-bind:striped="true">
                <template #cell(name)="data">
                    <router-link v-bind:to="{ name: 'mailing', params: { id: data.item.id }}">{{ data.value }}</router-link>
                </template>
                <template #cell(state)="data">
                    {{ itemState(data) }}
                </template>
                <template #table-caption>{{ $t('components.mailings.table.footer', {count: filteredData.length, total: data.length}) }}</template>
            </b-table>

        </div>
        <div v-else>{{ $t('errors.unauthorized')}}</div>
    </div>
</template>

<script>
    export default {
        name: 'AppMailings',
        data() {
            return {
                columns: [
                    { key: 'name', label: this.$t('components.mailings.column.name'), sortable: true },
                    { key: 'study.name', label: this.$t('components.mailings.column.study'), sortable: true },
                    { key: 'owner.name', label: this.$t('components.mailings.column.owner'), sortable: true },
                    { key: 'state', label: this.$t('components.mailings.column.state'), sortable: true },
                    { key: 'count', label: this.$t('components.mailings.column.count'), sortable: true },
                ],
                data: [],
            };
        },
        computed: {
            filteredData() {

                return this.data;
            },
        },
        methods: {
            async loadData() {
                try {
                    this.$app.loading();
                    /*
                     * Load data
                     */
                    this.data = await this.$app.mailings_all();

                } finally {

                    this.$app.loaded();
                }
            },
            itemState(data) {

                switch(data && data.item && data.item.state) {
                    case null: return this.$t('models.mailing.state.created');
                    case 0: return this.$t('models.mailing.state.stopped');
                    case 1: return this.$t('models.mailing.state.running');
                    case 2: return this.$t('models.mailing.state.finished');
                    default: return this.$t('models.mailing.state.unknown');
                }
            },
        },
        mounted() {
            this.$app.log('App mailings component mounted.');

            this.loadData();
        },
    };
</script>

<style lang="scss">
    .app-mailings {}
</style>
