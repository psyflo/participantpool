<template>
    <div class="app-user">
        <h3 v-if="createUser">{{ $t('components.user.title') }}</h3><h3 v-else>{{ $t('components.user.title') }}:&nbsp;{{ userName }}</h3>
        <hr/>

        <div v-if="$app.security_authenticated && $app.security_can({ context: $app.security.context.USER, ability: $app.security.ability.READ })">

            <b-form v-on:submit="onSave">
                <b-form-group v-bind:label="`${$t('components.user.fields.name')}:`" label-for="name" v-bind:invalid-feedback="$app.validation_getError('name')" v-bind:state="$app.validation_isValid('name')">
                    <b-form-input id="name" v-model="data.name" placeholder="Enter name" v-bind:required="true" v-bind:state="$app.validation_isValid('name')"></b-form-input>
                </b-form-group>

                <div v-if="createUser">

                    <b-form-group v-bind:label="`${$t('components.user.fields.email')}:`" label-for="email" v-bind:invalid-feedback="$app.validation_getError('email')" v-bind:state="$app.validation_isValid('email')">
                        <b-form-input id="email" v-model="data.email" type="email" placeholder="Enter email" v-bind:required="true" v-bind:state="$app.validation_isValid('email')"></b-form-input>
                    </b-form-group>

                </div>
                <div v-else>

                    <b-form-group v-bind:label="`${$t('components.user.fields.email')}:`" label-for="email" v-bind:invalid-feedback="$app.validation_getError('email')" v-bind:state="$app.validation_isValid('email')">
                        <b-form-input id="email" v-model="data.email" type="email" placeholder="Enter email" v-bind:state="$app.validation_isValid('email')" readonly></b-form-input>
                    </b-form-group>

                </div>

                <div v-if="createUser">

                    <b-form-group v-bind:label="`${$t('components.user.fields.password')}:`" label-for="password" v-bind:invalid-feedback="$app.validation_getError('password')" v-bind:state="$app.validation_isValid('password')">
                        <b-form-input id="password" v-model="data.password" type="password" placeholder="Enter password" v-bind:required="true" v-bind:state="$app.validation_isValid('password')"></b-form-input>
                    </b-form-group>

                    <b-form-group v-bind:label="`${$t('components.user.fields.password_confirmation')}:`" label-for="password_confirmation" v-bind:invalid-feedback="$app.validation_getError('password_confirmation')" v-bind:state="$app.validation_isValid('password_confirmation')">
                        <b-form-input id="password_confirmation" v-model="data.password_confirmation" type="password" placeholder="Confirm password" v-bind:required="true" v-bind:state="$app.validation_isValid('password_confirmation')"></b-form-input>
                    </b-form-group>

                </div>

                <b-form-group v-bind:label="`${$t('components.user.fields.role')}:`" label-for="role" description="">
                    <b-form-radio-group id="role" v-model="data.role" v-bind:options="role.options"></b-form-radio-group>
                </b-form-group>

                <hr/>
                
                <b-button type="button" variant="secondary" v-on:click="onCancel">{{ $t('components.user.button.cancel') }}</b-button>
                
                <fieldset v-if="createUser === false">
                    <b-button type="button" variant="info" v-on:click="onRefresh">{{ $t('components.user.button.refresh') }}</b-button>
                </fieldset>

                <fieldset v-bind:disabled="false === $app.security_can({ context: $app.security.context.USER, ability: $app.security.ability.UPDATE })">
                    <b-button type="submit" variant="primary">{{ $t('components.user.button.save') }}</b-button>
                </fieldset>

                <fieldset v-if="createUser === false" v-bind:disabled="false === $app.security_can({ context: $app.security.context.USER, ability: $app.security.ability.DELETE })">
                    <b-button type="button" variant="danger" v-b-modal.modalDelete>{{ $t('components.user.button.delete') }}</b-button>
                    <b-modal id="modalDelete" v-bind:title="`${ $t('components.user.modal.delete.title') }`" v-on:ok="onDelete">{{ $t('components.user.modal.delete.message') }}</b-modal>
                </fieldset>
            </b-form>

        </div>
        
        <div v-else>{{ $t('errors.unauthorized')}}</div>
    </div>
</template>

<script>
    export default {
        name: 'AppUser',
        props: ['id'],
        data() {
            return {
                data: {
                    role: 'disabled',
                },
                role: {
                    options: [
                        { value: 'disabled', text: this.$t('components.user.roles.disabled') },
                        { value: 'manager', text: this.$t('components.user.roles.manager') },
                        { value: 'admin', text: this.$t('components.user.roles.admin') },
                    ],
                },
            };
        },
        computed: {
            userName() {
                if (this.data && this.data.name) {

                    return `${this.data.name}`;
                }

                return null;
            },
            createUser() {
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
                    this.data = await this.$app.users_get(this.id);

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

                /*
                 * Email address is required and has to be valid
                 */
                if (typeof this.data.email === 'undefined' || this.data.email === null || this.data.email === '') {

                    this.$app.validation_setError['email'] = 'Email required.';

                } else {
                    /*
                     * Example from https://v2.vuejs.org/v2/cookbook/form-validation.html#Using-Custom-Validation
                     *
                     * if (/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(this.data.email) === false) {
                     */
                    if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(this.data.email) === false) {

                        this.$app.validation_setError['email'] = 'Email address is not valid.';

                    }
                }

                /*
                 * Password is required, has to been at least 8 chars and needs to be confirmed
                 */
                if (this.createUser) {

                    if (typeof this.data.password === 'undefined' || this.data.password === null || this.data.password === '') {

                        this.$app.validation_setError('password', 'Password required.');

                    } else {

                        if (this.data.password.length < 8) {

                            this.$app.validation_setError('password', 'Password has to be at least 8 chars.');

                        } else {

                            if (this.data.password !== this.data.password_confirmation) {

                                this.$app.validation_setError('password_confirmation', 'Confirmation does not match with password.');
                            }
                        }
                    }
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
                            this.data = await this.$app.users_add(this.data);

                            /*
                             * Move back to list and forward to saved user to force reload
                             */
                            this.$router.push({ name: 'users', params: {} });
                            this.$router.push({ name: 'user', params: { id: this.data.id } });

                        } else {
                            /*
                             * Update data
                             */
                            this.data = await this.$app.users_save(this.id, this.data);
                        }

                        this.$app.toast_showSuccess('Data saved.');
                    }

                } catch (error) {

                    this.$app.toast_showError(`Save data failed with error: ${error.message}`);
                    this.$app.validation_readError(error);
                    this.$forceUpdate();

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
                        this.data = await this.$app.users_delete(this.id);
                        /*
                         * Show success and navigate back
                         */
                        this.$router.push({ name: 'users', params: {} });
                        this.$app.toast_showSuccess('Data deleted.');
                    }

                } catch (error) {

                    this.$app.toast_showError(`Delete data failed with error: ${error.message}`);

                } finally {

                    this.$app.loaded();
                }
            },
            onCancel() {
                this.$router.push({ name: 'users', params: {} });
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
            this.$app.log('App user component mounted.', this.data);
            
            this.loadData();
        },
    };
</script>

<style lang="scss">
    .app-user {
        fieldset {
            display: inline-block;
        }
    }
</style>
