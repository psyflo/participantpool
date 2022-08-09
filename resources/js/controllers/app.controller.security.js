/*
 * App security controller
 */
export default {
    data() {
        return {};
    },
    methods: {
        async security_get() {
            try {
                const response = await axios.get(this.http_apiURL(`security`));
                return response.data;

            } catch (error) {
                
                this.error('Failed get security', error);
                throw (error.response && error.response.data ? error.response.data : { message: 'Unknown error' });
            }
        },
    },
};
