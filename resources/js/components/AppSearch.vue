<template>
    <div class="app-search">
        <div class="accordion" role="tablist" v-if="enabled">
            <b-card no-body class="mb-1">
                <b-card-header header-tag="header" role="tab">
                    <b-button v-b-toggle.accordion-filter variant="light">{{ $t('components.search.filter.title' )}}</b-button>
                    <span class="badge badge-secondary">{{ $app.search_filters.length }}</span>
                    <button type="button" class="btn btn-link link-secondary" v-on:click="$app.search_clearFilters">{{ $t('components.search.filter.clear') }}</button>
                </b-card-header>
                <b-collapse id="accordion-filter" accordion="my-accordion" role="tabpanel" v-bind:visible="collapsed">
                    <b-card-body>
                        <table class="table caption-top align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>
                                        <b-form-select v-model="$app.search.logical" v-bind:options="$app.search_logicals"></b-form-select>
                                    </th>
                                    <th>
                                        <b-form-select v-model="$app.search.column" v-bind:options="$app.search_columns" v-on:change="$app.search_columnChanged"></b-form-select>
                                    </th>
                                    <th>
                                        <b-form-select v-model="$app.search.exclude" v-bind:options="$app.search_excludes"></b-form-select>
                                    </th>
                                    <th>
                                        <b-form-select v-model="$app.search.compare" v-bind:options="$app.search_compares" v-on:change="$app.search_compareChanged"></b-form-select>
                                    </th>
                                    <th>
                                        <b-form-input v-if="$app.search.column === null || ($app.search.column && $app.search.column.type !== 'select')" v-bind:type="$app.search.column && $app.search.column.type" v-model="$app.search.text"></b-form-input>
                                        <b-form-select v-if="$app.search.column && $app.search.column.type === 'select' && $app.search.column.key === 'mailings'" v-model="$app.search.text" v-bind:options="$app.search_mailings"></b-form-select>
                                        <b-form-select v-if="$app.search.column && $app.search.column.type === 'select' && $app.search.column.key === 'studies'" v-model="$app.search.text" v-bind:options="$app.search_studies"></b-form-select>
                                    </th>
                                    <th>
                                        <b-form-input v-if="$app.search.compare && $app.search.compare === 'between'" v-bind:type="$app.search.column && $app.search.column.type" v-model="$app.search.moretext"></b-form-input>
                                    </th>
                                    <th>
                                        <b-button-group>
                                            <button type="button" class="btn btn-primary" v-on:click="$app.search_addFilter"><i class="fa-solid fa-plus"></i></button>
                                            <button type="button" class="btn btn-secondary" v-on:click="$app.search_clearInput"><i class="fa-solid fa-xmark"></i></button>
                                        </b-button-group>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(filter, index) in $app.search.filters" v-bind:key="index">
                                    <td>{{ $t(`components.search.filter.algo.${filter.logical}`) }}</td>
                                    <td>{{ filter.column.label }}</td>
                                    <td>{{ $t(`components.search.filter.exclude.${filter.exclude?'yes':'no'}`) }}</td>
                                    <td>{{ $t(`components.search.filter.compare.${filter.compare}`) }}</td>
                                    <td>{{ $app.search_output(filter) }}</td>
                                    <td>{{ $app.search.compare === 'between' && filter.column && filter.column.type === 'date' ? new Date(filter.moretext).toLocaleString('de', {year: 'numeric', month: '2-digit', day: '2-digit'}) : filter.moretext }}</td>
                                    <td><button type="button" class="btn btn-dark" v-on:click="$app.search_removeFilter(index)"><i class="fa-solid fa-trash"></i></button></td>
                                </tr>
                            </tbody>
                        </table>
                    </b-card-body>
                </b-collapse>
            </b-card>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'AppSearch',
        props: { enabled: Boolean },
        data() {
            return {
                collapsed: true,
            };
        },
        computed: {},
        methods: {},
        mounted() {
            this.$app.log('App search component mounted.');
            
            this.$app.search_loadMailings();
            this.$app.search_loadStudies();
        },
    };
</script>

<style lang="scss">
    .app-search {}
</style>
