<template>
    <div class="app-users">
        <h3>{{ $t('components.users.title') }}</h3>

        <div v-if="$app.security_authenticated && $app.security_can({ context: $app.security.context.USER, ability: $app.security.ability.READ })">

            <div>
                <b-button variant="link" v-on:click="loadData">{{ $t('components.users.actions.refresh') }}</b-button>
                <router-link v-bind:to="{ name: 'user', params: { id: 0 }}">{{ $t('components.users.actions.create') }}</router-link>
            </div>

            <b-table v-bind:items="filteredData" v-bind:fields="columns" responsive="sm" v-bind:striped="true">
                <template #cell(name)="data">
                    <router-link v-bind:to="{ name: 'user', params: { id: data.item.id }}">{{ data.value }}</router-link>
                </template>
                <template #cell(role)="data">
                    {{ data.value ? userRole(data.value) : '' }}
                </template>
                <template #table-caption>{{ $t('components.users.table.footer', {count: filteredData.length, total: data.length}) }}</template>
            </b-table>

        </div>
        <div v-else>{{ $t('errors.unauthorized')}}</div>
    </div>
</template>

<script>
    export default {
        name: 'AppUsers',
        data() {
            return {
                columns: [
                    { key: 'name', label: this.$t('components.users.column.name'), sortable: true },
                    { key: 'email', label: this.$t('components.users.column.email'), sortable: true },
                    { key: 'role', label: this.$t('components.users.column.role'), sortable: true },
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
            userRole(role) {

                switch(role) {
                    case null: return this.$t('models.user.role.none');
                    case 'disabled': return this.$t('models.user.role.disabled');
                    case 'manager': return this.$t('models.user.role.manager');
                    case 'admin': return this.$t('models.user.role.admin');
                }
            },
            async loadData() {
                try {
                    this.$app.loading();
                    /*
                     * Load data
                     */
                    this.data = await this.$app.users_all();

                } finally {

                    this.$app.loaded();
                }
            },
        },
        mounted() {
            this.$app.log('App users component mounted.');

            this.loadData();
        },
    };
</script>

<style lang="scss">
    .app-users {}
</style>
