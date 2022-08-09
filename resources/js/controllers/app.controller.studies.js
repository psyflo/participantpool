/*
 * App studies controller
 */
export default {
    data() {
        return {};
    },
    methods: {
        async studies_all() {
            this.$app.log('Get studies', arguments);

            try {
                const response = await axios.get(this.$app.http_apiURL(`studies`));
                return response.data;

            } catch (error) {
                
                this.$app.error('Failed get studies', error);
                throw (error.response && error.response.data ? error.response.data : { message: 'Unknown error' });
            }
        },
        async studies_get(id = null) {
            this.$app.log('Get study', id, arguments);

            if (id !== null) {

                try {
                    const response = await axios.get(this.$app.http_apiURL(`studies/${id}`));
                    return response.data;

                } catch (error) {
                    
                    this.$app.error('Failed to get study', error);
                    throw (error.response && error.response.data ? error.response.data : { message: 'Unknown error' });
                }
            }
        },
        async studies_add(data = null) {
            this.$app.log('Add study', data, arguments);

            if (data !== null) {

                try {
                    const response = await axios.post(this.$app.http_apiURL(`studies`), { data });
                    return response.data;

                } catch (error) {

                    this.$app.error('Failed to add study', error);
                    throw (error.response && error.response.data ? error.response.data : { message: 'Unknown error' });
                }
            }
        },
        async studies_save(id = null, data = {}) {
            this.$app.log('Save study', id, data, arguments);

            if (id !== null) {

                try {
                    const response = await axios.put(this.$app.http_apiURL(`studies/${id}`), { data });
                    return response.data;

                } catch (error) {

                    this.$app.error('Failed to save study', error);
                    throw (error.response && error.response.data ? error.response.data : { message: 'Unknown error' });
                }
            }
        },
        async studies_delete(id = null) {
            this.$app.log('Delete study', id, arguments);

            if (id !== null) {

                try {
                    const response = await axios.delete(this.$app.http_apiURL(`studies/${id}`), { id });
                    return response.data;

                } catch (error) {

                    this.$app.error('Failed to delete study', error);
                    throw (error.response && error.response.data ? error.response.data : { message: 'Unknown error' });
                }
            }
        },
    },
};
