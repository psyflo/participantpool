<template>
    <div class="app-logs">
        <h3>{{ $t('components.logs.title') }}</h3>

        <div v-if="$app.security_authenticated && $app.security_role({ role: $app.security.role.ADMIN })">

            <div>
                <b-button variant="link" v-on:click="loadData">{{ $t('components.logs.actions.refresh') }}</b-button>
                <!-- <div class="btn-group" role="group"><label class="btn" v-for="item in level.items" v-bind:key="item"><input type="checkbox" v-model="level.checked" v-bind:value="item"><span class="badge" v-bind:class="`level-${item}`">{{ item.toUpperCase() }}</span></label></div> -->
            </div>

            <b-table v-bind:items="filteredData" v-bind:fields="columns" responsive="sm" v-bind:striped="true">
                <template #cell(date)="data">
                    <span class="text-nowrap">{{ data.value ? (new Date(data.value)).toLocaleString('de', {year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit', second: '2-digit'}) : '' }}</span>
                </template>
                <template #cell(level)="data">
                    <div class="badge" v-bind:class="`level-${data.value}`">{{ data.value ? logLevel(data.value) : '' }}</div>
                </template>
                <template #table-caption>{{ $t('components.logs.table.footer', {count: filteredData.length, total: data.length}) }}</template>
            </b-table>

        </div>
        <div v-else>{{ $t('errors.unauthorized')}}</div>
    </div>
</template>

<script>
    export default {
        name: 'AppLogs',
        data() {
            return {
                columns: [
                    { key: 'date', label: this.$t('components.logs.column.date'), sortable: true },
                    { key: 'level', label: this.$t('components.logs.column.level'), sortable: true },
                    { key: 'message', label: this.$t('components.logs.column.message'), sortable: false },
                ],
                level: {
                    items: [ 'error', 'warning', 'info' ],
                    checked: [ 'error', 'warning' ],
                },
                data: [],
            };
        },
        computed: {
            filteredData() {

                return this.data.filter(item => {
                    let included = false;

                    if (Array.isArray(this.level.checked)) {

                        included = this.level.checked.includes(item.level);
                    };

                    return included;
                });
            },
        },
        methods: {
            logLevel(level) {

                return level.toUpperCase();
            },
            async loadData() {
                try {
                    this.$app.loading();
                    /*
                     * Load data
                     */
                    this.data = await this.$app.log_get();

                } finally {

                    this.$app.loaded();
                }
            },
        },
        mounted() {
            this.$app.log('App logs component mounted.');

            this.loadData();
        },
    };
</script>

<style lang="scss">
    .app-logs {
        .btn-group {
            vertical-align: revert;
        }
        .btn-group .btn span.badge {
            margin-left: 5px;
        }
        /*
         * Existing bootstrap badges (https://getbootstrap.com/docs/4.6/components/badge/)
         */
        .badge-danger {
            color: #fff;
            background-color: #dc3545;
        }
        .badge-warning {
            color: #212529;
            background-color: #ffc107;
        }
        .badge-info {
            color: #fff;
            background-color: #17a2b8;
        }
        .badge-dark {
            color: #fff;
            background-color: #343a40;
        }
        /*
         * Laravel log levels (https://laravel.com/docs/9.x/logging#writing-log-messages)
         */
        .level-error, .level-alert, .level-critical, .level-emergency {
            @extend .badge-danger;
        }
        .level-warning {
            @extend .badge-warning;
        }
        .level-notice, .level-info {
            @extend .badge-info;
        }
        .level-debug {
            @extend .badge-dark;
        }
    }
</style>
