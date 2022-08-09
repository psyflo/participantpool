/*
 * App configuration class
 */
export default {
    data() {
        return {
            config: {
                environment: process.env.NODE_ENV,
                debug: process.env.MIX_APP_DEBUG === 'true' ? true : false,
                appURL: process.env.MIX_APP_URL,
                version: process.env.MIX_APP_VERSION,
            }
        };
    },
    computed: {},
    methods: {
        config_appURL() {
            return this.config.appURL;
        },
        config_isDebug() {
            return this.config.debug;
        },
        config_getVersion() {
            return this.config.version;
        },
    },
};
