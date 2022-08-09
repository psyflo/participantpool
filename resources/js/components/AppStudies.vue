<template>
    <div class="app-studies">
        <h3>{{ $t('components.studies.title') }}</h3>

        <div v-if="$app.security_authenticated && $app.security_can({ context: $app.security.context.STUDY, ability: $app.security.ability.READ })">

            <div>
                <b-button variant="link" v-on:click="loadData">{{ $t('components.studies.actions.refresh') }}</b-button>
                <router-link v-bind:to="{ name: 'study', params: { id: 0 }}">{{ $t('components.studies.actions.create') }}</router-link>
            </div>

            <b-table v-bind:items="filteredData" v-bind:fields="columns" responsive="sm" v-bind:striped="true">
                <template #cell(name)="data">
                    <router-link v-bind:to="{ name: 'study', params: { id: data.item.id }}">{{ data.value }}</router-link>
                </template>
                <template #cell(starts_at)="data">
                    {{ data.value ? (new Date(data.value)).toLocaleDateString('de', {year: 'numeric', month: '2-digit', day: '2-digit'}) : '' }}
                </template>
                <template #cell(ends_at)="data">
                    {{ data.value ? (new Date(data.value)).toLocaleDateString('de', {year: 'numeric', month: '2-digit', day: '2-digit'}) : '' }}
                </template>
                <template #cell(updated_at)="data">
                    {{ data.value ? (new Date(data.value)).toLocaleString('de', {year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit'}) : '' }}
                </template>
                <template #table-caption>{{ $t('components.studies.table.footer', {count: filteredData.length, total: data.length}) }}</template>
            </b-table>

        </div>
        <div v-else>{{ $t('errors.unauthorized')}}</div>
    </div>
</template>

<script>
    export default {
        name: 'AppStudies',
        props: {},
        data() {
            return {
                columns: [
                    { key: 'name', label: 'Name', sortable: true },
                    { key: 'starts_at', label: 'Start', sortable: true },
                    { key: 'ends_at', label: 'End', sortable: true },
                    { key: 'mailings_count', label: 'Mailings', sortable: true },
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
                    this.data = await this.$app.studies_all();
   
                } finally {

                    this.$app.loaded();
                }
            },
        },
        mounted() {
            this.$app.log('App studies component mounted.');

            this.loadData();
        },
    };
</script>

<style lang="scss">
    .app-studies {}
</style>
