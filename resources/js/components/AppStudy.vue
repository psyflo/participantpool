<template>
    <div class="app-study">
        <h3 v-if="createStudy">{{ $t('components.study.title') }}</h3><h3 v-else>{{ $t('components.study.title') }}:&nbsp;{{ studyName }}</h3>
        <hr/>

        <div v-if="$app.security_authenticated && $app.security_can({ context: $app.security.context.STUDY, ability: $app.security.ability.READ })">

            <b-form v-on:submit="onSave">
                <b-form-group v-bind:label="`${$t('components.study.fields.name')}:`" label-for="name" v-bind:invalid-feedback="$app.validation_getError('name')" v-bind:state="$app.validation_isValid('name')">
                    <b-form-input id="name" v-model="data.name" placeholder="Enter name" v-bind:required="true" v-bind:state="$app.validation_isValid('name')"></b-form-input>
                </b-form-group>

                <b-form-group v-bind:label="`${$t('components.study.fields.starts_at')}:`" label-for="starts_at" description="">
                    <b-form-datepicker id="starts_at" v-model="data.starts_at" class="mb-2" v-bind:start-weekday="1" v-bind:locale="'de'" v-bind:value="`YYYY-MM-DD`" v-bind:date-format-options="{'year': 'numeric', 'month': '2-digit', 'day': '2-digit'}"></b-form-datepicker>
                </b-form-group>

                <b-form-group v-bind:label="`${$t('components.study.fields.ends_at')}:`" label-for="ends_at" description="">
                    <b-form-datepicker id="ends_at" v-model="data.ends_at" class="mb-2" v-bind:start-weekday="1" v-bind:locale="'de'" v-bind:value="`YYYY-MM-DD`" v-bind:date-format-options="{'year': 'numeric', 'month': '2-digit', 'day': '2-digit'}"></b-form-datepicker>
                </b-form-group>

                <b-form-group v-bind:label="`${$t('components.study.fields.description')}:`" label-for="ends_at" description="">
                    <b-form-textarea id="description" v-model="data.description" rows="3" max-rows="6"></b-form-textarea>
                </b-form-group>
                
                <div v-if="createStudy === false">
                    <p>{{ $t('components.study.fields.mailings') }}:</p>
                    <b-table v-bind:small="true" head-variant="light" v-bind:fields="mailings.fields" v-bind:items="data && data.mailings">
                        <template v-slot:cell(name)="data">
                            <router-link v-bind:to="{ name: 'mailing', params: { id: data.item.id }}">{{ data.value }}</router-link>
                        </template>
                        <template #cell(created_at)="data">
                            {{ data.value ? (new Date(data.value)).toLocaleString('de', {year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit'}) : '' }}
                        </template>
                        <template #head()="data">
                            <span class="font-weight-normal">{{ data.label }}</span>
                        </template>
                    </b-table>

                    <b-form-group v-bind:label="`${$t('components.study.fields.updated')}:`" label-for="updated" description="">
                        {{ data.updated_at ? (new Date(data.updated_at)).toLocaleString('de', {year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit', x_second: '2-digit'}) : '' }}
                    </b-form-group>
                </div>

                <hr/>
                
                <b-button type="button" variant="secondary" v-on:click="onCancel">{{ $t('components.study.button.cancel') }}</b-button>
                
                <fieldset v-if="createStudy === false">
                    <b-button type="button" variant="info" v-on:click="onRefresh">{{ $t('components.study.button.refresh') }}</b-button>
                </fieldset>
                
                <fieldset v-bind:disabled="false === $app.security_can({ context: $app.security.context.STUDY, ability: $app.security.ability.UPDATE })">
                    <b-button type="submit" variant="primary">{{ $t('components.study.button.save') }}</b-button>
                </fieldset>
                
                <fieldset v-if="createStudy === false" v-bind:disabled="false === $app.security_can({ context: $app.security.context.STUDY, ability: $app.security.ability.DELETE })">
                    <b-button type="button" variant="danger" v-b-modal.modalDelete>{{ $t('components.study.button.delete') }}</b-button>
                    <b-modal id="modalDelete" v-bind:title="`${ $t('components.study.modal.delete.title') }`" v-on:ok="onDelete">{{ $t('components.study.modal.delete.message') }}</b-modal>
                </fieldset>
            </b-form>

        </div>
        
        <div v-else>{{ $t('errors.unauthorized')}}</div>
    </div>
</template>

<script>
    export default {
        name: 'AppStudy',
        props: ['id'],
        data() {
            return {
                data: {},
                mailings: {
                    fields: [
                        { key: 'name', label: this.$t('components.study.mailings.columns.name') },
                        { key: 'count', label: this.$t('components.study.mailings.columns.count') },
                        { key: 'created_at', label: this.$t('components.study.mailings.columns.created_at') },
                    ],
                },
            };
        },
        computed: {
            studyName() {
                if (this.data && this.data.name) {

                    return `${this.data.name}`;
                }

                return null;
            },
            createStudy() {
                if (this.data && this.data.id) {

                    return false;
                }

                return true;
            }
        },
        methods: {
            async loadData() {
                try {
                    this.$app.loading();
                    /*
                     * Load data
                     */
                    this.data = await this.$app.studies_get(this.id);

                } finally {

                    this.$app.loaded();
                }
            },
            async validateData() {
                /*
                 * Name is required
                 */
                if (typeof this.data.name === 'undefined' || this.data.name === null || this.data.name === '') {

                    this.$app.validation_setError('name', 'Name required.');
                }
            },
            async saveData() {
                try {
                    this.$app.loading();

                    if (await this.$app.validation_validate(this.validateData)) {
    
                        if (parseInt(this.id) === 0) {
                            /*
                            * Create data
                            */
                            this.data = await this.$app.studies_add(this.data);
                            /*
                            * Move back to list and forward to saved participant to force reload
                            */
                            this.$router.push({ name: 'studies', params: {} });
                            this.$router.push({ name: 'study', params: { id: this.data.id } });

                        } else {
                            /*
                            * Update data
                            */
                            this.data = await this.$app.studies_save(this.id, this.data);
                        }

                        this.$app.toast_showSuccess('Data saved.');
                    }

                } catch (error) {

                    this.$app.toast_showError(`Save data failed with error: ${error.message}`)

                } finally {

                    this.$app.loaded();
                }
            },
            async deleteData() {
                try {
                    this.$app.loading();
                    
                    if (parseInt(this.id) !== 0) {
                        /*
                         * Delete data
                         */
                        this.data = await this.$app.studies_delete(this.id);
                        /*
                         * Show success and navigate back
                         */
                        this.$router.push({ name: 'studies', params: {} });
                        this.$app.toast_showSuccess('Data deleted.');
                    }

                } catch (error) {

                    this.$app.toast_showError(`Delete data failed with error: ${error.message}`)

                } finally {

                    this.$app.loaded();
                }
            },
            onCancel() {
                this.$router.push({ name: 'studies', params: {} });
            },
            onSave(event) {
                event.preventDefault();
                this.saveData();
            },
            onRefresh() {
                this.loadData();
            },
            onDelete() {
                this.deleteData();
            },
        },
        mounted() {
            this.$app.log('App study component mounted.', this.data);
            
            this.loadData();
        },
    };
</script>

<style lang="scss">
    .app-study {
        fieldset {
            display: inline-block;
        }
    }
</style>
