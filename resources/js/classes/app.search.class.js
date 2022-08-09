/*
 * App search class
 */
export default {
    data() {
        return {
            search: {
                logical: 'and',
                column: null,
                exclude: false,
                compare: 'equals',
                text: '',
                moretext: '',
                options: {
                    logicals: [
                        { text: this.$t('components.search.filter.algo.and'), value: 'and' },
                        { text: this.$t('components.search.filter.algo.or'), value: 'or' },
                    ],
                    excludes: [
                        { text: this.$t('components.search.filter.exclude.no'), value: false },
                        { text: this.$t('components.search.filter.exclude.yes'), value: true }
                    ],
                    compares: [
                        { text: this.$t('components.search.filter.compare.equals'), value: 'equals', types: ['text', 'number', 'date'] },
                        { text: this.$t('components.search.filter.compare.contains'),  value: 'contains', types: ['text', 'number', 'select'] },
                        { text: this.$t('components.search.filter.compare.between'), value: 'between', types: ['number', 'date'] },
                        { text: this.$t('components.search.filter.compare.greater'), value: 'greater', types: ['number', 'date'] },
                        { text: this.$t('components.search.filter.compare.lower'), value: 'lower', types: ['number', 'date'] },
                    ],
                    mailings: [],
                    studies: [],
                },
                columns: [],
                filters: [],
            },
        };
    },
    computed: {
        search_logicals() {
            return this.search.options.logicals;
        },
        search_excludes() {
            return this.search.options.excludes;
        },
        search_compares() {
            if (this.search.column) {

                return _.filter(this.search.options.compares, (compare) => { return _.includes(compare.types, this.search.column.type); });
            }

            return this.search.options.compares;
        },
        search_columns() {
            return this.search.columns;
        },
        search_filters() {
            return this.search.filters;
        },
        search_mailings() {
            return this.search.options.mailings;
        },
        search_studies() {
            return this.search.options.studies;
        },
    },
    methods: {
        search_setColumns(columns = []) {
            this.search.columns = columns;
        },
        search_filteredData(data = []) {
            if (this.search.filters.length !== 0) {

                return data.filter(item => {
                    let included = null;

                    /**
                     * Sample filter item
                     *
                     * Object {
                     *   algo: "and"
                     *   column: Object {
                     *     key: "name"
                     *     type:"text"
                     *   }
                     *   compare: "equals"
                     *   exclude: false
                     *   moretext: ""
                     *   text: "Jerde"
                     * }
                     */
                    this.search.filters.forEach(filter => {
                        let comparison = null;

                         /*
                          * Compare item value with filter text
                          */
                        if (item[filter.column.key] !== null) {
                       
                            switch(filter.compare) {
                                case 'equals':
                                    comparison = item[filter.column.key].toLowerCase() === filter.text.toLowerCase();
                                    break;
                                case 'contains':
                                    if (filter.column.type === 'select') {
                                        if (Array.isArray(item[filter.column.key])) {
                                            //comparison = item[filter.column.key].includes(parseInt(filter.text));
                                            comparison = item[filter.column.key].includes(filter.text);
                                        };
                                    } else {
                                        comparison = item[filter.column.key].toLowerCase().includes(filter.text.toLowerCase());
                                    }
                                    break;
                                case 'between':
                                    if (filter.column.type === 'date') {
                                        comparison = (new Date(item[filter.column.key]).getTime() >= new Date(filter.text).getTime() && new Date(item[filter.column.key]).getTime() <= new Date(filter.moretext).getTime());
                                    } else if (filter.column.type === 'number') {
                                        comparison = (parseInt(item[filter.column.key]) >= parseInt(filter.text) && parseInt(item[filter.column.key]) <= parseInt(filter.moretext));
                                    }
                                    break;
                                case 'greater':
                                    if (filter.column.type === 'date') {
                                        comparison = new Date(item[filter.column.key]).getTime() > new Date(filter.text).getTime();
                                    } else if (filter.column.type === 'number') {
                                        comparison = parseInt(item[filter.column.key]) > parseInt(filter.text);
                                    }
                                    break;
                                case 'lower':
                                    if (filter.column.type === 'date') {
                                        comparison = new Date(item[filter.column.key]).getTime() < new Date(filter.text).getTime();
                                    } else if (filter.column.type === 'number') {
                                        comparison = parseInt(item[filter.column.key]) < parseInt(filter.text);
                                    }
                                    break;
                                default:
                                    comparison = false;
                            }
                        } else {

                            comparison = false;
                        }

                        /*
                         * Turn comparison if exclude is requested
                         */
                        if (filter.exclude) {

                            comparison = !comparison;
                        }

                        /*
                         * Do not use algorithm with first filter
                         */
                        if (included === null) {

                            included = comparison;

                        } else {

                            switch(filter.logical) {
                                case 'and':
                                    included = included && comparison;
                                    break;
                                case 'or':
                                    included = included || comparison;
                                    break;
                            }
                        }
                    });

                    return included;
                });
            }

            return data;
        },
        search_output(filter) {
            if (filter.column && filter.column.type === 'date') {

                return new Date(filter.text).toLocaleString('de', {year: 'numeric', month: '2-digit', day: '2-digit'});

            } else if (filter.column && filter.column.type === 'select' && filter.column.key === 'mailings') {
                
                //const found = _.find(this.search_mailings, { value: parseInt(filter.text) });
                const found = _.find(this.search_mailings, { value: filter.text });

                //return `${found.text} (${found.value})`;
                return `${found.text}`;

            } else if (filter.column && filter.column.type === 'select' && filter.column.key === 'studies') {
                
                //const found = _.find(this.search_studies, { value: parseInt(filter.text) });
                const found = _.find(this.search_studies, { value: filter.text });

                //return `${found.text} (${found.value})`;
                return `${found.text}`;

            } else {
                
                return filter.text;
            }
        },
        search_clearInput() {
            /*
             * Clear search input
             */
            _.assign(this.search, { logical: 'and', column: null, exclude: false, compare: 'equals', text: '', moretext: '' });
        },
        search_addFilter() {
            this.search.filters.push({ logical: this.search.logical, column: this.search.column, exclude: this.search.exclude, compare: this.search.compare, text: this.search.text, moretext: this.search.moretext });
        },
        search_removeFilter(index) {
            this.search.filters.splice(index, 1);
        },
        search_clearFilters() {
            this.search.filters = [];
        },
        search_columnChanged() {
            /*
             * Clear input texts when changing column
             */
            _.assign(this.search, { text: '', moretext: '' });
        },
        search_compareChanged() {
            /*
             * Clear more text input when compare is not between
             */
            if (this.search.compare !== 'between') {
                
                _.assign(this.search, { moretext: '' });
            }
        },
        async search_loadMailings() {
            try {
                const data = await this.mailings_all();
                //this.search.options.mailings = data.map((mailing) => { return { value: mailing.id, text: mailing.name }; });
                this.search.options.mailings = data.map((mailing) => { return { value: mailing.name, text: mailing.name }; });

            } catch (error) {

                this.error('Search mailing loading failed.', error);
            }
        },
        async search_loadStudies() {
            try {
                const data = await this.studies_all();
                //this.search.options.studies = data.map((study) => { return { value: study.id, text: study.name }; });
                this.search.options.studies = data.map((study) => { return { value: study.name, text: study.name }; });

            } catch (error) {

                this.error('Search studies loading failed.', error);
            }
        },

    },
    created() {
        this.log('Search class created.');
    },
};
