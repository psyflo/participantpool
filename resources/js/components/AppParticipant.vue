<template>
    <div class="app-participant">
        <h3 v-if="createParticipant">{{ $t('components.participant.title') }}</h3><h3 v-else>{{ $t('components.participant.title') }}:&nbsp;{{ participantName }}</h3>
        <hr/>

        <div v-if="$app.security_authenticated && $app.security_can({ context: $app.security.context.PARTICIPANT, ability: $app.security.ability.READ })">

            <b-form v-on:submit="onSave">
                <b-form-group v-bind:label="`${$t('components.participant.fields.firstname')}:`" label-for="firstname">
                    <b-form-input id="firstname" v-model="data.firstname" placeholder="Enter firstname"></b-form-input>
                </b-form-group>

                <b-form-group v-bind:label="`${$t('components.participant.fields.name')}:`" label-for="name" v-bind:invalid-feedback="inputError('name')" v-bind:state="isInputValid('name')">
                    <b-form-input id="name" v-model="data.name" placeholder="Enter name" v-bind:required="true" v-bind:state="isInputValid('name')"></b-form-input>
                </b-form-group>

                <b-form-group v-bind:label="`${$t('components.participant.fields.email')}:`" label-for="email" v-bind:invalid-feedback="inputError('email')" v-bind:state="isInputValid('email')">
                    <b-form-input id="email" v-model="data.email" type="email" placeholder="Enter email" v-bind:required="true" v-bind:state="isInputValid('email')"></b-form-input>
                </b-form-group>

                <b-form-group v-bind:label="`${$t('components.participant.fields.gender')}:`" label-for="gender" description="">
                    <b-form-radio-group id="gender" v-model="data.gender" v-bind:options="gender.options"></b-form-radio-group>
                </b-form-group>

                <b-form-group v-bind:label="`${$t('components.participant.fields.birthdate')}:`" label-for="birthdate" description="" v-bind:invalid-feedback="inputError('birthdate')" v-bind:state="isInputValid('birthdate')">
                    <b-form-input id="birthdate" v-model="participantBirthdate" v-bind:lazy="true" placeholder="Enter date in format dd.mm.yyyy" v-bind:state="isInputValid('birthdate')"></b-form-input>
                </b-form-group>

                <b-form-group v-bind:label="`${$t('components.participant.fields.location')}:`" label-for="location" description="">
                    <b-form-input id="location" v-model="data.location" placeholder="Enter location"></b-form-input>
                </b-form-group>

                <b-form-group v-bind:label="`${$t('components.participant.fields.education')}:`" label-for="education" description="">
                    <div class="row">
                        <div class="col-sm">
                            <b-form-select v-model="data.education" v-bind:options="education.options"></b-form-select>
                        </div>
                        <div class="col-sm">
                            <b-form-select v-model="data.education_topic" v-bind:options="education.topics" v-bind:disabled="false === educationWithTopic"></b-form-select>
                        </div>
                    </div>
                </b-form-group>

                <b-form-group v-bind:label="`${$t('components.participant.fields.language')}:`" label-for="language" description="">
                    <b-form-radio-group id="language" v-model="data.language" v-bind:options="language.options"></b-form-radio-group>
                </b-form-group>

                <b-form-group v-bind:label="`${$t('components.participant.fields.survey_languages')}:`" label-for="survey_languages" description="">
                    <b-form-checkbox-group id="survey_languages" v-model="participantSurveyLanguages" v-bind:options="language.options"></b-form-checkbox-group>
                </b-form-group>

                <b-form-group v-bind:label="`${$t('components.participant.fields.study_interest')}:`" label-for="study_interest" description="">
                    <b-form-checkbox-group id="study_interest" v-model="participantStudyInterest" v-bind:options="study_interest.options"></b-form-checkbox-group>
                </b-form-group>

                <div v-if="createParticipant === false">
                    <p>{{ $t('components.participant.fields.mailings') }}:</p>
                    <b-table v-bind:small="true" head-variant="light" v-bind:fields="mailings.fields" v-bind:items="data && data.mailings">
                        <template v-slot:cell(name)="data">
                            <router-link v-bind:to="{ name: 'mailing', params: { id: data.item.id }}">{{ data.value }}</router-link>
                        </template>
                        <template v-slot:cell(pivot.mail_sent)="data">
                            {{ data.value ? (new Date(data.value)).toLocaleString('de', {year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit'}) : '' }}
                        </template>
                        <template #head()="data">
                            <span class="font-weight-normal">{{ data.label }}</span>
                        </template>
                    </b-table>

                    <b-form-group v-bind:label="`${$t('components.participant.fields.studies')}:`" label-for="studies" description="">
                        <vue-multiselect id="studies" v-model="data.studies" v-bind:options="studies.options" label="name" trackBy="id" v-bind:multiple="true"></vue-multiselect>
                    </b-form-group>

                    <b-form-group v-bind:label="`${$t('components.participant.fields.updated')}:`" label-for="updated" description="">
                        {{ data.updated_at ? (new Date(data.updated_at)).toLocaleString('de', {year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit', x_second: '2-digit'}) : '' }}
                    </b-form-group>
                </div>

                <hr/>

                <b-button type="button" variant="secondary" v-on:click="onCancel">{{ $t('components.participant.button.cancel') }}</b-button>
                
                <fieldset v-if="createParticipant === false">
                    <b-button type="button" variant="info" v-on:click="onRefresh">{{ $t('components.mailing.button.refresh') }}</b-button>
                </fieldset>
                
                <fieldset v-bind:disabled="false === $app.security_can({ context: $app.security.context.PARTICIPANT, ability: $app.security.ability.UPDATE })">
                    <b-button type="submit" variant="primary">{{ $t('components.participant.button.save') }}</b-button>
                </fieldset>

                <fieldset v-bind:disabled="false === $app.security_can({ context: $app.security.context.PARTICIPANT, ability: $app.security.ability.UPDATE })">
                    <b-button type="button" variant="warning" v-on:click="onSendLink">{{ $t('components.participant.button.sendlink') }}</b-button>
                </fieldset>
                
                <fieldset v-if="createParticipant === false" v-bind:disabled="false === $app.security_can({ context: $app.security.context.PARTICIPANT, ability: $app.security.ability.DELETE })">
                    <b-button type="button" variant="danger" v-b-modal.modalDelete>{{ $t('components.participant.button.delete') }}</b-button>
                    <b-modal id="modalDelete" v-bind:title="`${ $t('components.participant.modal.delete.title') }`" v-on:ok="onDelete">{{ $t('components.participant.modal.delete.message') }}</b-modal>
                </fieldset>
            </b-form>

        </div>
        <div v-else>{{ $t('errors.unauthorized')}}</div>
    </div>
</template>

<script>
    Vue.component('vue-multiselect', window.VueMultiselect.default);

    export default {
        name: 'AppParticipant',
        props: ['id'],
        data() {
            return {
                data: {},
                errors: [],
                gender: {
                    options: [
                        { value: 'F', text: this.$t('components.participant.genders.female') },
                        { value: 'M', text: this.$t('components.participant.genders.male') },
                        { value: 'N', text: this.$t('components.participant.genders.nonbinary') },
                        { value: 'D', text: this.$t('components.participant.genders.notdisclose') },
                        { value: 'S', text: this.$t('components.participant.genders.selfdescribe') },
                    ],
                },
                education: {
                    options: [
                        { value: null, text: '' },
                        { value: 'UNI', text: this.$t('components.participant.education.options.uni') },
                        { value: 'FH', text: this.$t('components.participant.education.options.fh') },
                        { value: 'HIGH', text: this.$t('components.participant.education.options.high') },
                        { value: 'APP', text: this.$t('components.participant.education.options.app') },
                        { value: 'BASIC', text: this.$t('components.participant.education.options.basic') },
                        { value: 'NONE', text: this.$t('components.participant.education.options.none') },
                    ],
                    topics: [
                        { value: null, text: '' },
                        { value: 'MED', text: this.$t('components.participant.education.topics.medicine') },
                        { value: 'SCIENCE', text: this.$t('components.participant.education.topics.science') },
                        { value: 'PSYCH', text: this.$t('components.participant.education.topics.psychology') },
                        { value: 'LAW', text: this.$t('components.participant.education.topics.law') },
                        { value: 'LANG', text: this.$t('components.participant.education.topics.languages') },
                        { value: 'ECO', text: this.$t('components.participant.education.topics.economics') },
                        { value: 'OTHER', text: this.$t('components.participant.education.topics.other') },
                    ],
                },
                language: {
                    options: [
                        { value: 'DE', text: this.$t('components.participant.language.options.german') },
                        { value: 'EN', text: this.$t('components.participant.language.options.english') },
                        { value: 'FR', text: this.$t('components.participant.language.options.french') },
                        { value: 'IT', text: this.$t('components.participant.language.options.italian') },
                    ],
                },
                study_interest: {
                    options: [
                        { value: 'O', text: this.$t('components.participant.study_interest.options.online') },
                        { value: 'L', text: this.$t('components.participant.study_interest.options.lab') },
                    ],
                },
                mailings: {
                    fields: [
                        { key: 'name', label: this.$t('components.participant.mailings.columns.name') },
                        { key: 'study.name', label: this.$t('components.participant.mailings.columns.study') },
                        { key: 'pivot.mail_sent', label: this.$t('components.participant.mailings.columns.sent') },
                    ],
                },
                studies: {
                    options: [],
                },
            };
        },
        computed: {
            participantName() {
                if (this.data && this.data.name) {

                    if (this.data.firstname && this.data.age) {

                        return `${this.data.firstname} ${this.data.name} (${this.data.age})`;

                    } else if (this.data.firstname) {

                        return `${this.data.firstname} ${this.data.name}`;

                    } else if (this.data.age) {

                        return `${this.data.name} (${this.data.age})`;
                    }

                    return `${this.data.name}`;
                }

                return null;
            },
            participantBirthdate: {
                get: function() {
                    if (this.data && this.data.birthdate) {
                        if (moment(this.data.birthdate, 'YYYY-MM-DD').isValid()) {

                            return moment(this.data.birthdate, 'YYYY-MM-DD').format('DD.MM.YYYY');
                        }

                        return this.data.birthdate;
                    }

                    return null;
                },
                set: function(value) {
                    if (this.data && moment(value, 'DD.MM.YYYY').isValid()) {

                        this.data.birthdate = moment(value, 'DD.MM.YYYY').format('YYYY-MM-DD');

                    } else {

                        this.data.birthdate = value;
                    }
                }
            },
            participantSurveyLanguages: {
                get: function() {
                    if (this.data && this.data.survey_languages) {

                        return this.data.survey_languages.split(',');
                    }

                    return [];
                },
                set: function(value) {
                    if (this.data) {

                        this.data.survey_languages = value.join(',');
                    }
                }
            },
            participantStudyInterest: {
                get: function() {
                    if (this.data && this.data.study_interest) {

                        return this.data.study_interest.split(',');
                    }

                    return [];
                },
                set: function(value) {
                    if (this.data) {

                        this.data.study_interest = value.join(',');
                    }
                }
            },
            educationWithTopic() {
                if (this.data && this.data.education) {

                    return this.data.education === 'UNI' || this.data.education === 'FH';
                }

                return false;
            },
            createParticipant() {
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
                    this.data = await this.$app.participants_get(this.id);

                    this.studies.options = await this.$app.studies_all();

                } finally {

                    this.$app.loaded();
                }
            },
            isInputValid(name) {
                return (typeof this.errors[name] === 'undefined') ? null : false;
            },
            inputError(name) {
                if (this.isInputValid(name) === false) {

                    return this.errors[name];
                }

                return null;
            },
            async validateData() {
                this.errors = {};
                /*
                 * Name is required
                 */
                if (typeof this.data.name === 'undefined' || this.data.name === null || this.data.name === '') {

                    this.errors['name'] = 'Name required.';
                }
                /*
                 * Email address is required and has to be valid
                 */
                if (typeof this.data.email === 'undefined' || this.data.email === null || this.data.email === '') {

                    this.errors['email'] = 'Email required.';

                } else {
                    /*
                     * Example from https://v2.vuejs.org/v2/cookbook/form-validation.html#Using-Custom-Validation
                     *
                     * if (/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(this.data.email) === false) {
                     */
                    if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(this.data.email) === false) {

                        this.errors['email'] = 'Email address is not valid.';

                    } else {
                        /*
                         * Only check for email already exists if creating new participant, else we will get an error in case of the updating record
                         */
                        if (parseInt(this.id) === 0) {

                            const validation = await this.$app.participants_validate_email(this.data.email);

                            if (validation.valid === false) {

                                this.errors['email'] = 'Email address does already exist.';
                            }
                        }
                    }
                }
                /*
                 * Check birthdate format, but it is not required, therefore only if its filled in
                 */
                if (this.data.birthdate) {
                    if (moment(this.data.birthdate, 'YYYY-MM-DD').isValid() === false) {

                        this.errors['birthdate'] = 'Date is not in correct format.';
                    }
                }

                /*
                 * Force reload to show validation error
                 */
                this.$forceUpdate();

                return _.isEmpty(this.errors);
            },
            async saveData() {
                try {
                    this.$app.loading();

                    if (await this.validateData()) {
    
                        if (parseInt(this.id) === 0) {
                            /*
                            * Create data
                            */
                            this.data = await this.$app.participants_add(this.data);
                            /*
                            * Move back to list and forward to saved participant to force reload
                            */
                            this.$router.push({ name: 'participants', params: {} });
                            this.$router.push({ name: 'participant', params: { id: this.data.id } });

                        } else {
                            /*
                            * Update data
                            */
                            this.data = await this.$app.participants_save(this.id, this.data);
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
                        this.data = await this.$app.participants_delete(this.id);
                        /*
                         * Show success and navigate back
                         */
                        this.$router.push({ name: 'participants', params: {} });
                        this.$app.toast_showSuccess('Data deleted.');
                    }

                } catch (error) {

                    this.$app.toast_showError(`Delete data failed with error: ${error.message}`)

                } finally {

                    this.$app.loaded();
                }
            },
            async sendLink() {
                try {
                    this.$app.loading();
                    
                    if (parseInt(this.id) !== 0) {
                        /*
                         * Send link
                         */
                        this.data = await this.$app.participants_sendlink(this.id);
                        /*
                         * Show success
                         */
                        this.$app.toast_showSuccess('Link sent to participant.');
                    }

                } catch (error) {

                    this.$app.toast_showError(`Send link failed with error: ${error.message}`)

                } finally {

                    this.$app.loaded();
                }
            },
            onCancel() {
                this.$router.push({ name: 'participants', params: {} });
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
            onSendLink() {
                this.sendLink();
            },
        },
        mounted() {
            this.$app.log('App participant component mounted.', this.data);
            
            this.loadData();
        },
    };
</script>

<style lang="scss">
    .app-participant {
        $primary: #007bff;

        fieldset {
            display: inline-block;
        }
        .multiselect__tag {
            background: $primary;
        }
    }
</style>
