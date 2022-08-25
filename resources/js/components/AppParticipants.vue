<template>
    <div class="app-participants row justify-content-center">
        <div class="col-md-12">
            <h3>{{ $t('components.participants.title') }}</h3>

            <div v-if="$app.security_authenticated && $app.security_can({ context: $app.security.context.PARTICIPANT, ability: $app.security.ability.READ })">

                <app-search v-bind:enabled="true"></app-search>

                <div>
                    <b-button variant="link" v-on:click="refreshData" v-bind:disabled="dataLoading">{{ $t('components.participants.actions.refresh') }}&nbsp;<span class="spinner-border spinner-border-sm" v-bind:class="{ visible: dataLoading, invisible: dataLoading === false }"></span></b-button>
                    <router-link v-bind:to="{ name: 'participant', params: { id: 0 }}">{{ $t('components.participants.actions.create') }}</router-link>
                    <b-button variant="link" v-b-modal.modalPopover>{{ $t('components.participants.actions.mailing') }}</b-button>
                    
                    <b-modal id="modalPopover" v-bind:title="`${ $t('components.participants.actions.mailing') }`" v-on:show="initMailing" v-on:ok="createMailing">
                        <b-form-group v-bind:label="`${ $t('components.participants.modal.mailing.name') }`" label-for="name" v-bind:invalid-feedback="`Name is required.`" v-bind:state="mailing.errors.name">
                            <b-form-input id="name" type="text" v-model="mailing.name" v-bind:state="mailing.errors.name"></b-form-input>
                        </b-form-group>
                        <b-form-group v-bind:label="`${ $t('components.participants.modal.mailing.study') }`" label-for="study" v-bind:invalid-feedback="`Study is required.`" v-bind:state="mailing.errors.study">
                            <b-form-select id="study" v-model="mailing.study" v-bind:options="mailing.studies" v-bind:state="mailing.errors.study"></b-form-select>
                        </b-form-group>
                        <b-form-group v-bind:label="`${ $t('components.participants.modal.mailing.random') } (${this.mailing.random.min} - ${this.mailing.random.max})`" label-for="random" v-bind:invalid-feedback="`Random value must be between ${this.mailing.random.min} and ${this.mailing.random.max}`" v-bind:state="mailing.errors.random">
                            <b-form-input id="random" type="number" v-model="mailing.random.value" v-bind:min="mailing.random.min" v-bind:max="mailing.random.max" v-bind:state="mailing.errors.random"></b-form-input>
                        </b-form-group>
                    </b-modal>
                </div>

                <b-table id="my-table" v-bind:items="filteredData" v-bind:fields="enabledColumns" vbind:stacked="false" v-bind:responsive="true" v-bind:striped="true" v-bind:per-page="pagination.perPage" v-bind:current-page="pagination.current" v-bind:primary-key="`id`">
                    <template #cell(name)="data">
                        <router-link class="text-nowrap" v-bind:to="{ name: 'participant', params: { id: data.item.id }}">{{ data.value }}</router-link>
                    </template>
                    <template #cell(email)="data">
                        <a v-bind:href="`mailto:${ data.value }`">{{ data.value }}</a>
                    </template>
                    <template #cell(birthdate)="data">
                        {{ data.value ? (new Date(data.value)).toLocaleString('de', {year: 'numeric', month: '2-digit', day: '2-digit'}) : '' }}
                    </template>
                    <template #cell(last_contact)="data">
                        {{ data.value ? (new Date(data.value)).toLocaleString('de', {year: 'numeric', month: '2-digit', day: '2-digit'}) : '' }}
                    </template>
                    <template #cell(mailings)="data">
                        <span class="text-nowrap">{{ data.value ? data.value.join(';') : '' }}</span>
                    </template>
                    <template #cell(studies)="data">
                        <span class="text-nowrap">{{ data.value ? data.value.join(';') : '' }}</span>
                    </template>
                    <template #cell(updated_at)="data">
                        {{ data.value ? (new Date(data.value)).toLocaleString('de', {year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit'}) : '' }}
                    </template>
                    <template #cell()="data">
                        <span class="text-nowrap">{{ data.value }}</span>
                    </template>
                    <template #table-caption>
                        {{ $t('components.participants.table.pagination.footer', {from: paginationFrom, to: paginationTo, count: filteredData.length, total: data.length}) }}
                    </template>
                </b-table>

                <div><b-pagination v-model="pagination.current" v-bind:total-rows="paginationRows" v-bind:per-page="pagination.perPage" aria-controls="my-table"></b-pagination></div>

            </div>
            <div v-else>{{ $t('errors.unauthorized')}}</div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'AppParticipants',
        mixins: [],
        props: {},
        data() {
            return {
                columns: [
                    { key: 'name', label: this.$t('components.participants.column.name'), sortable: true, stickyColumn: true, enabled: true, filter: { enabled: true, type: 'text' } },
                    { key: 'firstname', label: this.$t('components.participants.column.firstname'), sortable: true, enabled: true, filter: { enabled: true, type: 'text' } },
                    { key: 'email', label: this.$t('components.participants.column.email'), sortable: true, enabled: false },
                    { key: 'email_verified', label: this.$t('components.participants.column.email_verified'), sortable: true, enabled: true, filter: { enabled: true, type: 'text' } },
                    { key: 'gender', label: this.$t('components.participants.column.gender'), sortable: true, enabled: true, filter: { enabled: true, type: 'text' } },
                    { key: 'birthdate', label: this.$t('components.participants.column.birthdate'), sortable: true, enabled: false },
                    { key: 'age', label: this.$t('components.participants.column.age'), sortable: true, enabled: true, filter: { enabled: true, type: 'number' } },
                    { key: 'location', label: this.$t('components.participants.column.location'), sortable: true, enabled: true, filter: { enabled: true, type: 'text' } },
                    { key: 'education', label: this.$t('components.participants.column.education'), sortable: true, enabled: true, filter: { enabled: true, type: 'text' } },
                    { key: 'education_topic', label: this.$t('components.participants.column.education_topic'), sortable: true, enabled: true, filter: { enabled: true, type: 'text' } },
                    { key: 'language', label: this.$t('components.participants.column.language'), sortable: true, enabled: true, filter: { enabled: true, type: 'text' } },
                    { key: 'survey_languages', label: this.$t('components.participants.column.survey_languages'), sortable: true, enabled: true, filter: { enabled: true, type: 'text' } },
                    { key: 'study_interest', label: this.$t('components.participants.column.study_interest'), sortable: true, enabled: true, filter: { enabled: true, type: 'text' } },
                    { key: 'last_contact', label: this.$t('components.participants.column.last_contact'), sortable: true, enabled: true, filter: { enabled: true, type: 'date' } },
                    { key: 'mailings', label: this.$t('components.participants.column.mailings'), sortable: false, enabled: true, filter: { enabled: true, type: 'select' } },
                    { key: 'studies', label: this.$t('components.participants.column.studies'), sortable: false, enabled: true, filter: { enabled: true, type: 'select' } },
                    { key: 'updated_at', label: this.$t('components.participants.column.updated_at'), sortable: true, enabled: false },
                ],
                data: [],
                dataLoading: false,
                pagination: {
                    current: 1,
                    perPage: 20,
                    initialSize: 100,
                    chunkSize: 1000,
                },
                mailing: {
                    name: null,
                    study: null,
                    random: { min: 1, max: 1, value: null },
                    studies: [{ text: 'NEW', value: 0 }],
                    errors: { name: null, study: null, random: null }
                },
            };
        },
        computed: {
            enabledColumns() {
                return _.filter(this.columns, { enabled: true });
            },
            filteredData() {
                return this.$app.search_filteredData(this.data);
            },
            paginationRows() {
                return this.filteredData.length;
            },
            paginationFrom() {
                return ( 1 + ( this.pagination.current - 1 ) * this.pagination.perPage );
            },
            paginationTo() {
                return ( this.pagination.current * this.pagination.perPage );
            },
        },
        methods: {
            refreshData() {
                if (this.dataLoading === false) {
                    
                    this.loadData();
                }
            },
            async loadData() {
                try {
                    /*
                     * Start loading data process
                     */
                    this.dataLoading = true;
                    this.$app.loading();

                    /*
                     * Load initial data
                     */
                    this.data = await this.$app.participants_all(0, this.pagination.initialSize);
                    
                    /*
                     * Load data chunks
                     */
                    this.loadDataChunk();

                } finally {

                    this.$app.loaded();
                }
            },
            async loadDataChunk() {
                try {
                    let nextChunk = 0;
                    let lastChunkSize = 0;

                    do {
                        /*
                         * Load next chunk
                         */
                        const chunk = await this.$app.participants_all((this.pagination.initialSize + (nextChunk * this.pagination.chunkSize)), this.pagination.chunkSize);
                        
                        /*
                         * Prepare for next chunk
                         */
                        lastChunkSize = chunk.length;
                        nextChunk++;

                        /*
                         * Insert chunk data
                         */
                        this.data.push(...chunk);

                    } while (lastChunkSize > 0)

                } finally {
                    /*
                     * Finish loading data process
                     */
                    this.dataLoading = false;
                }
            },
            initSearch() {
                /*
                 * Map all columns with enabled filter
                 */
                const columns = this.columns.map((column) => {
                    if (column.filter && column.filter.enabled) {

                        return { text: column.label, value: { key: column.key, label: column.label, type: column.filter.type } };
                    }

                    return false;
                });
                /*
                 * Set search columns, filter out each column with filter disabled
                 */
                this.$app.search_setColumns(columns.filter(column => column !== false));
            },
            async createMailing(bvModalEvent) {
                try {
                    let validation = false;

                    /*
                     * Validate inputs, error values must be false to show error message otherwise null to do not
                     */
                    this.mailing.errors.name = this.mailing.name !== null && this.mailing.name !== '' ? null : false;
                    this.mailing.errors.study = this.mailing.study !== null ? null : false;
                    this.mailing.errors.random = this.mailing.random.value !== null && parseInt(this.mailing.random.value) >= this.mailing.random.min && parseInt(this.mailing.random.value) <= this.mailing.random.max ? null : false;

                    /*
                     * Check whole validation
                     */
                    validation = this.mailing.errors.name === null && this.mailing.errors.study === null && this.mailing.errors.random === null;

                    /*
                     * Create mailing if validation was successful or prevent closing modal window to show errors
                     */
                    if (validation) {

                        const response = await this.$app.mailings_add({ mailing: { name: this.mailing.name, study_id: this.mailing.study, random: parseInt(this.mailing.random.value) }, participants: this.filteredData });
                        this.$app.toast_showSuccess('Mailing created.');
                        this.$router.push({ name: 'mailing', params: { id: response.id } });

                    } else {

                        bvModalEvent.preventDefault();
                    }

                } catch(error) {

                    this.$app.toast_showError(error.message);
                }
            },
            async initMailing() {
                try {
                    const response = await this.$app.studies_all();
                    this.mailing.studies.push(...response.map((study) => { return { value: study.id, text: study.name }; }));
                    this.mailing.random.value = this.mailing.random.max = this.filteredData.length;
                    
                } catch(error) {

                    this.$app.error(error);
                }
            },
        },
        mounted() {
            this.$app.log('App participants component mounted.');

            this.loadData();
            this.initSearch();
        },
    };
</script>

<style lang="scss">
    .app-participants {
        div.table-caption {
            display: inline-block;
        }
    }
</style>
