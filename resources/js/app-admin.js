/**
 * Install Vue
 * 
 * See: https://vuejs.org/
 */
window.Vue = require('vue').default;

/**
 * Install BootstrapVue with IconsPlugin, ToastPlugin
 * 
 * See: https://bootstrap-vue.org/
 */
const BootstrapVue = require('bootstrap-vue');
window.Vue.use(BootstrapVue);
window.Vue.use(BootstrapVue.IconsPlugin);
window.Vue.use(BootstrapVue.ToastPlugin);

/**
 * Install PortalVue for ToastPlugin
 * 
 * See: https://portal-vue.linusb.org/
 */
const PortalVue = require('portal-vue');
window.Vue.use(PortalVue);

/**
 * Install Vue I18n
 * 
 * See: https://kazupon.github.io/vue-i18n/
 */
const VueI18n = require('vue-i18n').default;
window.Vue.use(VueI18n);

const i18n = new VueI18n({
    locale: process.env.MIX_APP_LANG,
    fallbackLocale: 'en',
    messages: { de: require('./locales/de.json'), en: require('./locales/en.json') },
});

/**
 * Register Vue components
 */
const AppParticipants = Vue.component('app-participants', require('./components/AppParticipants.vue').default);
const AppStudies = Vue.component('app-studies', require('./components/AppStudies.vue').default);
const AppDashboard = Vue.component('app-dashboard', require('./components/AppDashboard.vue').default);
const AppParticipant = Vue.component('app-participant', require('./components/AppParticipant.vue').default);
const AppMailings = Vue.component('app-mailings', require('./components/AppMailings.vue').default);
const AppMailing = Vue.component('app-mailing', require('./components/AppMailing.vue').default);
const AppSearch = Vue.component('app-search', require('./components/AppSearch.vue').default);
const AppStudy = Vue.component('app-study', require('./components/AppStudy.vue').default);
const AppUsers = Vue.component('app-users', require('./components/AppUsers.vue').default);
const AppUser = Vue.component('app-user', require('./components/AppUser.vue').default);
const AppProfile = Vue.component('app-profile', require('./components/AppProfile.vue').default);
const AppLogs = Vue.component('app-log', require('./components/AppLogs.vue').default);
const AppSettings = Vue.component('app-settings', require('./components/AppSettings.vue').default);

/**
 * Configure Vue Router
 */
window.VueRouter = require('vue-router').default;

const router = new VueRouter({
    mode: 'history',
    base: '/participantpool',
    routes: [
        { path: '/admin', name: 'dashboard', component: AppDashboard },
        { path: '/admin/participants', name: 'participants', component: AppParticipants },
        { path: '/admin/participants/:id', name: 'participant', component: AppParticipant, props: true },
        { path: '/admin/studies', name: 'studies', component: AppStudies },
        { path: '/admin/studies/:id', name: 'study', component: AppStudy, props: true },
        { path: '/admin/mailings', name: 'mailings', component: AppMailings },
        { path: '/admin/mailings/:id', name: 'mailing', component: AppMailing, props: true },
        { path: '/admin/users', name: 'users', component: AppUsers },
        { path: '/admin/users/:id', name: 'user', component: AppUser, props: true },
        { path: '/admin/profile', name: 'profile', component: AppProfile },
        { path: '/admin/logs', name: 'logs', component: AppLogs },
        { path: '/admin/settings', name: 'settings', component: AppSettings },
    ],
});

/**
 * Load mixin classes
 */
const classConfig = require('./classes/app.config.class').default;
const classHttp = require('./classes/app.http.class').default;
const classToast = require('./classes/app.toast.class').default;
const classSecurity = require('./classes/app.security.class').default;
const classSearch = require('./classes/app.search.class').default;
const classValidation = require('./classes/app.validation.class').default;

/**
 * Load mixin controllers
 */
const controllerParticipants = require('./controllers/app.controller.participants').default;
const controllerStudies = require('./controllers/app.controller.studies').default;
const controllerMailings = require('./controllers/app.controller.mailings').default;
const controllerSecurity = require('./controllers/app.controller.security').default;
const controllerUsers = require('./controllers/app.controller.users').default;
const controllerProfile = require('./controllers/app.controller.profile').default;
const controllerLog = require('./controllers/app.controller.log').default;
const controllerSettings = require('./controllers/app.controller.settings').default;

/**
 * Init application
 */
const app = new window.Vue({
    router, i18n,
    mixins: [classConfig, classHttp, classToast, classSecurity, classSearch, classValidation, controllerParticipants, controllerStudies, controllerMailings, controllerSecurity, controllerUsers, controllerProfile, controllerLog, controllerSettings],
    data() {
        return {
            overlay: false,
            loader: 0,
        }
    },
    methods: {
        log(message, ...data) { if (this.config_isDebug()) { console.log(message, data); } },
        error(message, ...data) { console.error(message, data); },
        clone(item) { return JSON.parse(JSON.stringify(item)); },
        loading() { this.loader++; this.overlay = true; },
        loaded() { this.loader--; if (this.loader === 0) { this.overlay = false; } },
    },
    created() {
        this.log('App class initialized', this);
    },
    beforeDestroy() {
        this.log('App class before destroy', this);
    },
});

/**
 * Define application global accessible
 */
window.Vue.prototype.$app = app;

/**
 * Start application
 */
app.$mount('#app');
