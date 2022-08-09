/*
 * App log controller
 */
export default {
    data() {
        return {};
    },
    methods: {
        async log_get() {
            try {
                const response = await axios.get(this.http_apiURL(`log`));
                return response.data;

            } catch (error) {
                
                this.error('Failed get log', error);
                throw (error.response && error.response.data ? error.response.data : { message: 'Unknown error' });
            }
        },
    },
};
