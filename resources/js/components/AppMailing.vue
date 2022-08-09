<template>
    <div class="app-mailing">
        <h3>{{ $t('components.mailing.title') }}:&nbsp;{{ data && data.name }}</h3>
        <hr/>

        <div v-if="$app.security_authenticated && $app.security_can({ context: $app.security.context.MAILING, ability: $app.security.ability.READ })">

            <b-form v-on:submit="onSave">
                <div v-if="data.state === null">
                    <b-form-group label="Name:" label-for="name">
                        <b-form-input id="name" v-model="data.name" v-bind:disabled="data.state !== null" placeholder="Enter name" v-bind:required="true"></b-form-input>
                    </b-form-group>
                </div>
                
                <p>{{ $t('components.mailing.study') }}:&nbsp;{{ data && data.study && data.study.name }}</p>

                <p>{{ $t('components.mailing.owner') }}:&nbsp;{{ data && data.owner && data.owner.name }}</p>

                <p>{{ $t('components.mailing.state') }}:&nbsp;{{ mailingState }}</p>

                <p>{{ $t('components.mailing.participants') }}:</p>
                <b-table v-bind:small="true" head-variant="light" v-bind:fields="tableFields" v-bind:items="data && data.participants">
                    <template #cell(id)="data">
                        <b-button variant="link" size="sm" class="text-danger" v-on:click="removeParticipant(data.value)"><i class="fa-solid fa-xmark"></i></b-button>
                    </template>
                    <template #cell(participant.name)="data">
                        <router-link v-bind:to="{ name: 'participant', params: { id: data.item.participant.id }}">{{ data.value }}</router-link>
                    </template>
                    <template #cell(mail_sent)="data">
                        {{ data.value ? (new Date(data.value)).toLocaleString('de', {year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit', second: '2-digit'}) : '' }}
                    </template>
                    <template #head()="data">
                        <span class="font-weight-normal">{{ data.label }}</span>
                    </template>
                </b-table>

                <div v-if="data.state === null">
                    <b-form-group v-bind:label="`${$t('components.mailing.subject')}:`" label-for="subject">
                        <b-form-input id="subject" v-model="data.subject" v-bind:disabled="data.state !== null" placeholder="" v-bind:required="true"></b-form-input>
                    </b-form-group>
                </div>
                <div v-else>
                    <p>{{ $t('components.mailing.subject') }}:&nbsp;{{ data && data.subject }}</p>
                </div>
                
                <div v-if="data.state === null">
                    <b-form-group v-bind:label="`${$t('components.mailing.content')}:`" label-for="content" v-bind:description="`You can use fields from participants table above as placeholders, as example for name {{ name }}.`">
                        <tinymce-editor id="tinyeditor" v-model="data.content" v-bind:init="{ height: 400, menubar: false, plugins: ['link'], toolbar: 'undo redo | link | bold italic | bullist numlist | removeformat', placeholder: 'Hello {{ firstname }} {{ name }} ...'}"></tinymce-editor>
                    </b-form-group>
                </div>
                <div v-else>
                    <p>{{ $t('components.mailing.content') }}:</p><p v-html="data.content"></p>
                </div>

                <hr/>

                <b-button type="button" variant="secondary" v-on:click="onCancel">{{ $t('components.mailing.button.cancel') }}</b-button>
                
                <b-button type="button" variant="info" v-on:click="onRefresh">{{ $t('components.mailing.button.refresh') }}</b-button>
                
                <fieldset v-bind:disabled="false === ( ( $app.security_can({ context: $app.security.context.MAILING, ability: $app.security.ability.UPDATE }) || $app.security_owner({ object: data }) ) && data.state === null )">
                    <b-button type="submit" variant="primary">{{ $t('components.mailing.button.save') }}</b-button>
                </fieldset>
                
                <fieldset v-bind:disabled="false === ( ( $app.security_can({ context: $app.security.context.MAILING, ability: $app.security.ability.UPDATE }) || $app.security_owner({ object: data }) ) && (data.state === null || data.state === 0) )">
                    <b-button type="button" variant="success" v-on:click="onSend">{{ $t('components.mailing.button.send') }}</b-button>
                </fieldset>
                
                <fieldset v-bind:disabled="false === ( ( $app.security_can({ context: $app.security.context.MAILING, ability: $app.security.ability.UPDATE }) || $app.security_owner({ object: data }) ) && data.state === 1 )">
                    <b-button type="button" variant="warning" v-on:click="onStop">{{ $t('components.mailing.button.stop') }}</b-button>
                </fieldset>
                
                <fieldset v-bind:disabled="false === ( ( $app.security_can({ context: $app.security.context.MAILING, ability: $app.security.ability.DELETE }) || $app.security_owner({ object: data }) ) && data.state === null )">
                    <b-button type="button" variant="danger" v-b-modal.modalDelete>{{ $t('components.mailing.button.delete') }}</b-button>
                    <b-modal id="modalDelete" v-bind:title="`${ $t('components.mailing.modal.delete.title') }`" v-on:ok="onDelete">{{ $t('components.mailing.modal.delete.message') }}</b-modal>
                </fieldset>
            </b-form>

        </div>
        <div v-else>{{ $t('errors.unauthorized')}}</div>
    </div>
</template>

<script>
    import tinymce from '@tinymce/tinymce-vue'

    export default {
        name: 'AppMailing',
        components: { 'tinymce-editor': tinymce },
        props: ['id'],
        data() {
            return {
                data: {},
                participants: {
                    fields: [
                        { key: 'id', label: '' },
                        { key: 'participant.name', label: this.$t('components.mailing.columns.name') },
                        { key: 'participant.firstname', label: this.$t('components.mailing.columns.firstname') },
                        { key: 'participant.email', label: this.$t('components.mailing.columns.email') },
                        { key: 'mail_sent', label: this.$t('components.mailing.columns.sent') },
                    ],
                    fields_noaction: [
                        { key: 'participant.name', label: this.$t('components.mailing.columns.name') },
                        { key: 'participant.firstname', label: this.$t('components.mailing.columns.firstname') },
                        { key: 'participant.email', label: this.$t('components.mailing.columns.email') },
                        { key: 'mail_sent', label: this.$t('components.mailing.columns.sent') },
                    ],
                },
            };
        },
        computed: {
            tableFields() {
                if (this.data && this.data.state !== null) {

                    return this.participants.fields_noaction;
                }

                return this.participants.fields;
            },
            mailingState() {
                if (this.data && typeof this.data.state !== undefined) {

                    switch(this.data.state) {
                        case null: return this.$t('models.mailing.state.created');
                        case 0: return this.$t('models.mailing.state.stopped');
                        case 1: return this.$t('models.mailing.state.running');
                        case 2: return this.$t('models.mailing.state.finished');
                        default: return this.$t('models.mailing.state.unknown');
                    }
                }
            },
        },
        methods: {
            async loadData() {
                try {
                    this.$app.loading();
                    /*
                     * Load data
                     */
                    this.data = await this.$app.mailings_get(this.id);

                } finally {

                    this.$app.loaded();
                }
            },
            async removeParticipant(id) {
                try {
                    this.$app.loading();
                    /*
                     * Remove participant
                     */
                    this.data = await await this.$app.mailings_participant_remove(this.id, id);

                } finally {

                    this.$app.loaded();
                }
            },
            async saveData({ state = null } = {}) {
                /*
                 * Store old state to restore after error
                 */
                const _state = this.data.state;

                try {
                    this.$app.loading();
                    /*
                     * Set state
                     */
                    this.data.state = state ?? null;
                    /*
                     * Save data
                     */
                    this.data = await this.$app.mailings_save(this.data);

                    this.$app.toast_showSuccess('Data saved.');

                } catch (error) {
                    /*
                     * Someting went wrong, restore previous state and show error
                     */
                    this.data.state = _state;
                    this.$app.toast_showError(error.message);

                } finally {

                    this.$app.loaded();
                }
            },
            async deleteData({ id = 0 } = {}) {
                try {
                    this.$app.loading();
                    /*
                     * Delete data
                     */
                    this.data = await this.$app.mailings_delete({ id });
                    /*
                     * Delete successful, navigate back
                     */
                    this.$app.toast_showSuccess('Mailing deleted.');
                    this.$router.push({ name: 'mailings', params: {} });

                } catch (error) {

                    this.$app.toast_showError(error.message);

                } finally {

                    this.$app.loaded();
                }
            },
            onSave(event) {
                event.preventDefault();
                this.saveData();
            },
            onCancel() {
                this.$router.push({ name: 'mailings', params: {} });
            },
            onSend() {
                this.saveData({ state: 1 });
            },
            onStop() {
                this.saveData({ state: 0 });
            },
            onDelete() {
                this.deleteData(this.data);
            },
            onRefresh() {
                this.loadData();
            },
        },
        mounted() {
            this.$app.log('App mailing component mounted.', this.data);
            
            this.loadData();
        },
    };
</script>

<style lang="scss">
    .app-mailing {
        fieldset {
            display: inline-block;
        }
    }
</style>
