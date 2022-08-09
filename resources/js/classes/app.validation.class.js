/**
 * Define validation mixin
 */
 export default {
    data() {
        return {
            validation: {
                errors: {},
            },
        };
    },
    methods: {
        async validation_validate(fn) {
            /*
             * Clear last validation errors
             */
            this.validation.errors = {};

            /*
             * Run validation function
             */
            if (typeof fn === 'function') { await fn(); }

            return _.isEmpty(this.validation.errors);
        },
        validation_isValid(name) {

            return (typeof this.validation.errors[name] === 'undefined') ? null : false;
        },
        validation_getError(name) {

            return this.validation_isValid(name) ? null : this.validation.errors[name];
        },
        validation_setError(name, error = null) {

            this.validation.errors[name] = error;
        },
        validation_readError(error = {}) {
            this.log('Validation read error', error);

            if (error && error.errors && typeof error.errors === 'object') {

                Object.keys(error.errors).forEach(key => {
                    this.log('Add error', key, error.errors[key]);

                    this.$app.validation_setError(key, Array.isArray(error.errors[key]) ? error.errors[key][0] : error.errors[key]);
                });
            }
        }
    },
};
