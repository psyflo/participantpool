<template>
    <div class="app-settings">
        <h3>{{ $t('components.settings.title') }}</h3>

        <div v-if="$app.security_authenticated && $app.security_role({ role: $app.security.role.ADMIN })">

            <div>
                <b-button variant="link" v-on:click="loadData">{{ $t('components.settings.actions.refresh') }}</b-button>
            </div>

            <b-table v-bind:items="filteredData" v-bind:fields="columns" responsive="sm" v-bind:striped="true">
                <template #cell(key)="data">
                    <b-button variant="link" v-b-modal.modalPopover v-on:click="changeSetting(data)">{{ data.value }}</b-button>
                </template>
                <template #cell(value)="data">
                    <span class="text-nowrap">{{ data.value }}</span>
                </template>
                <template #cell(default)="data">
                    <span class="text-nowrap">{{ data.value }}</span>
                </template>
                <template #table-caption>{{ $t('components.settings.table.footer', {count: filteredData.length, total: data.length}) }}</template>
            </b-table>

            <b-modal id="modalPopover" v-bind:title="`${ $t('components.settings.modal.title') }`" v-on:ok="saveSetting" v-on:cancel="cancelSetting" v-on:close="cancelSetting" v-on:hide="cancelSetting">
                <b-form-group v-bind:label="setting.key" label-for="value" v-bind:description="setting.description">
                    <b-form-input id="value" type="text" v-model="setting.value"></b-form-input>
                </b-form-group>
            </b-modal>

        </div>
        <div v-else>{{ $t('errors.unauthorized')}}</div>
    </div>
</template>

<script>
    export default {
        name: 'AppSettings',
        data() {
            return {
                columns: [
                    { key: 'key', label: this.$t('components.settings.column.key'), sortable: true },
                    { key: 'value', label: this.$t('components.settings.column.value'), sortable: false },
                    { key: 'default', label: this.$t('components.settings.column.default'), sortable: false },
                    { key: 'description', label: this.$t('components.settings.column.description'), sortable: false },
                ],
                setting: {
                    id: null,
                    key: null,
                    value: null,
                    description: null,
                },
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
                    this.data = await this.$app.settings_all();

                } finally {

                    this.$app.loaded();
                }
            },
            async changeSetting(data) {
                
                this.setting = {
                    id: data.item.id,
                    key: data.item.key,
                    value: data.item.value || '',
                    description: data.item.description,
                };
            },
            async cancelSetting() {
                
                this.setting =  {
                    id: null,
                    key: null,
                    value: null,
                    description: null,
                };
            },
            async saveSetting(bvModalEvent) {
                try {
                    this.$app.loading();

                    /*
                     * Validation
                     */
                    let validation = this.setting.key !== null;

                    /*
                     * Save setting if validation was successful or prevent closing modal window to show errors
                     */
                    if (validation) {
                        /*
                         * Save data
                         */
                        await this.$app.settings_save(this.setting.id, this.setting);
                        
                        this.$app.toast_showSuccess('Setting saved.');

                        /*
                         * Reload data
                         */
                        this.loadData();

                    } else {

                        bvModalEvent.preventDefault();
                    }

                } catch(error) {

                    this.$app.toast_showError(`Save setting failed with error: ${error.message}`);
                }
                finally {

                    this.$app.loaded();
                }
            },
        },
        mounted() {
            this.$app.log('App settings component mounted.');

            this.loadData();
        },
    };
</script>

<style lang="scss">
    .app-settings {}
</style>
