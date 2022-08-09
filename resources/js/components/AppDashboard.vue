<template>
    <div class="app-dashboard">
        <h3>{{ $t('components.dashboard.title') }}</h3>
        <p>Statistics of Participants:&nbsp;(<b-button variant="link" v-on:click="loadData">{{ $t('components.dashboard.actions.refresh') }}</b-button>)</p>
        <canvas id="chart"></canvas>
        <div class="footer">
            <p class="version text-muted">Version:&nbsp;{{ $app.config_getVersion() }}</p>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'AppDashboard',
        data() {
            return {
                data: null,
                chart: null,
            };
        },
        methods: {
            async loadData() {
                try {
                    this.$app.loading();
                    /*
                     * Load data
                     */
                    this.data = await this.$app.participants_statistics();
                    this.initCharts();

                } finally {

                    this.$app.loaded();
                }
            },
            initCharts() {
                /*
                 * Its required to destory current chart before recreate it
                 */
                if (this.chart !== null) {

                    this.chart.destroy();
                }

                this.chart = new window.Chart(document.getElementById('chart'), { type: 'line', data: this.data, options: {} });
            },
        },
        mounted() {
            this.$app.log('App dashboard component mounted.');

            this.loadData();
        },
    };
</script>

<style lang="scss">
    .app-dashboard {
        div.footer { margin-top: 2em; }
    }
</style>
