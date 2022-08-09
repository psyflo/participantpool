<template>
    <div class="app-user">
        <h3>{{ $t('components.user.title') }}:&nbsp;{{ userName }}</h3>
        <hr/>

        <div v-if="$app.security_authenticated">

            <b-form v-on:submit="onSave">
                <b-form-group v-bind:label="`${$t('components.user.fields.name')}:`" label-for="name" v-bind:invalid-feedback="$app.validation_getError('name')" v-bind:state="$app.validation_isValid('name')">
                    <b-form-input id="name" v-model="data.name" placeholder="Enter name" v-bind:required="true" v-bind:state="$app.validation_isValid('name')"></b-form-input>
                </b-form-group>

                <b-form-group v-bind:label="`${$t('components.user.fields.email')}:`" label-for="email" v-bind:invalid-feedback="$app.validation_getError('email')" v-bind:state="$app.validation_isValid('email')">
                    <b-form-input id="email" v-model="data.email" type="email" placeholder="Enter email" v-bind:state="$app.validation_isValid('email')" readonly></b-form-input>
                </b-form-group>

                <b-form-group v-bind:label="`${$t('components.user.fields.password')}:`" label-for="password" v-bind:invalid-feedback="$app.validation_getError('password')" v-bind:state="$app.validation_isValid('password')">
                    <b-form-input id="password" v-model="data.password" type="password" placeholder="Enter password" v-bind:state="$app.validation_isValid('password')"></b-form-input>
                </b-form-group>

                <b-form-group v-bind:label="`${$t('components.user.fields.password_confirmation')}:`" label-for="password_confirmation" v-bind:invalid-feedback="$app.validation_getError('password_confirmation')" v-bind:state="$app.validation_isValid('password_confirmation')">
                    <b-form-input id="password_confirmation" v-model="data.password_confirmation" type="password" placeholder="Confirm password" v-bind:state="$app.validation_isValid('password_confirmation')"></b-form-input>
                </b-form-group>

                <p>{{ $t('components.user.fields.role') }}:&nbsp;{{ userRole }}</p>
                
                <hr/>
                
                <b-button type="button" variant="secondary" v-on:click="onCancel">{{ $t('components.user.button.cancel') }}</b-button>
                
                <fieldset>
                    <b-button type="button" variant="info" v-on:click="onRefresh">{{ $t('components.user.button.refresh') }}</b-button>
                </fieldset>

                <fieldset>
                    <b-button type="submit" variant="primary">{{ $t('components.user.button.save') }}</b-button>
                </fieldset>
            </b-form>

        </div>
        
        <div v-else>{{ $t('errors.unauthorized')}}</div>
    </div>
</template>

<script>
    export default {
        name: 'AppProfile',
        data() {
            return {
                data: {},
            };
        },
        computed: {
            userName() {
                if (this.data && this.data.name) {

                    return `${this.data.name}`;
                }

                return null;
            },
            userRole() {
                if (this.data && this.data.role) {

                    return this.$t(`components.user.roles.${this.data.role}`);
                }

                return null;
            },
        },
        methods: {
            async loadData() {
                try {
                    this.$app.loading();
                    /*
                     * Load data
                     */
                    this.data = await this.$app.profile_get();

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
                 * Password is required, has to been at least 8 chars and needs to be confirmed
                 */
                if (typeof this.data.password === 'undefined' || this.data.password === null || this.data.password === '') {

                    // Not show an error, save profile without change password is allowed

                } else {

                    if (this.data.password.length < 8) {

                        this.$app.validation_setError('password', 'Password has to be at least 8 chars.');

                    } else {

                        if (this.data.password !== this.data.password_confirmation) {

                            this.$app.validation_setError('password_confirmation', 'Confirmation does not match with password.');
                        }
                    }
                }
            },
            async saveData() {
                try {
                    this.$app.loading();

                    if (await this.$app.validation_validate(this.validateData)) {
                        /*
                         * Save data
                         */
                        this.data = await this.$app.profile_save(this.data);
                        
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
            onCancel() {
                this.$router.push({ name: 'dashboard', params: {} });
            },
            onSave(event) {
                event.preventDefault();
                this.saveData();
            },
            onRefresh() {
                this.loadData();
            },
        },
        mounted() {
            this.$app.log('App profile component mounted.', this.data);
            
            this.loadData();
        },
    };
</script>

<style lang="scss">
    .app-profile {
        fieldset {
            display: inline-block;
        }
    }
</style>
