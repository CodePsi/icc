let tempArray = []

const store = new Vuex.Store({
    state: {
        sortKey: '',
        sortOrder: 1,
        headerColumns: []
    },
    getters: {
        getSortKey(state) {
            return state.sortKey
        },
        getSortOrder(state) {
            return state.sortOrder
        },
        getHeaderColumns(state) {
            return state.headerColumns
        }
    },
    mutations: {
        setSortKey(state, payload) {
            state.sortKey = payload
        },
        setSortOrder(state, payload) {
            state.sortOrder = payload
        },
        addHeaderColumn(state, payload) {
            state.headerColumns.push(payload)
        }
    }
});

const table = Vue.component('comp-table', {
    props: {
        data: Array,
        filterKey: String,
        initialSortKey: {
            type: String,
            required: false,
            default: ''
        },
        pagination: {
            type: Boolean,
            required: false,
            default: true
        },
        entriesPerPage: {
            type: Number,
            required: false,
            default: 10
        },
        sortingBy: {
            type: Array,
            required: false,
            default: function () {
                return []
            }
        },
        "table-row-class-name": {
            type: Function,
            required: false,
            default: function (row, index) {
                return '';
            }
        }
    },
    data() {
        return {
            currentPage: 1,
            pages: 1,
            pagesArray: [],
            countEntriesPerPage: this.entriesPerPage,
            displayGridData: [],
            allGridData: [],
            headerListKeys: [],
            duplicatesHeaderKeysCounter: 0
        }
    },
    mounted: function () {
        this.loadData();
    },
    watch: {
        data: function () {
            this.loadData();
        },
        sortKey: function () {
            if (this.sortKey !== '')
                this.updateData()
        },
        filterKey() {
            if (this.pagination) {
                this.updateData()
            }
        },
        sortOrder() {
            this.updateData();
        }
    },
    computed: {
        sortKey() {
            return store.getters.getSortKey
        },
        sortOrder() {
            return store.getters.getSortOrder
        },
        headerColumns() {
            return store.getters.getHeaderColumns
        },

    },
    filters: {
        capitalize: function (str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        }
    },
    methods: {
        filteredData() {
            const sortKey = this.sortKey;

            const filterKey = this.filterKey && this.filterKey.toLowerCase();
            // this.allGridData = this.data;
            if (this.displayGridData === undefined || this.displayGridData.length === 0) {
                this.setUpPagination();
            }
            let result = [...this.allGridData];
            if (filterKey) {
                result = this.allGridData.filter(function (row) {
                    return Object.keys(row).some(function (key) {
                        return String(row[key]).toLowerCase().indexOf(filterKey) > -1;
                    })
                })
            }
            if (this.sortKey) {
                let array = [];
                if (result !== this.displayGridData) {
                    array = result;
                } else {
                    array = [...this.allGridData];
                }
                array.sort((a, b) => {

                    a = a[sortKey];
                    b = b[sortKey];
                    return (a === b ? 0 : a > b ? 1 : -1) * this.sortOrder;
                })
                result = array;
            }
            this.allGridData = result
            this.setUpPagination()
        },
        loadData() {
            this.allGridData = this.data
            if (this.pagination) {
                this.updateData(true);

            } else {
                this.displayGridData = this.data
            }
        },
        sortBy: function (key) {
            this.sortKey = key;
            this.sortOrders[key] = this.sortOrders[key] * -1;
            if (this.pagination) {
                this.updateData();
            } else {
                this.displayGridData = this.allGridData
            }
        },
        updateData(leftSamePage = false) {
            this.filteredData();
            this.setUpPagination(leftSamePage)
        },
        setUpPagination(leftSamePage = false) {
            this.pages = Math.ceil(this.allGridData.length / this.countEntriesPerPage);
            if (!leftSamePage) {
                this.setPage(1);
            } else {
                this.setPage(this.currentPage)
            }
            this.displayGridData = this.allGridData.slice((this.currentPage - 1) * this.countEntriesPerPage, ((this.currentPage - 1) * this.countEntriesPerPage) + this.countEntriesPerPage);
        },
        setPage: function (page) {
            if (page !== '..') {
                if (page !== undefined)
                    this.currentPage = page;
                if (this.pages >= 4) {
                    if (this.currentPage === this.pages) {
                        this.pagesArray = [];
                        this.pagesArray.push(1, '..', this.currentPage - 1, this.currentPage);
                    } else if (this.currentPage === 1) {
                        this.pagesArray = [];
                        this.pagesArray.push(1, 2, '..', this.pages);
                    } else if (this.currentPage >= this.pages - 2) {
                        this.pagesArray = [];
                        if (this.currentPage === this.pages - 2)
                            this.pagesArray.push(1, '..', this.currentPage - 1, this.currentPage, this.currentPage + 1, this.pages);
                        else
                            this.pagesArray.push(1, '..', this.currentPage - 1, this.currentPage, this.currentPage + 1);
                    } else {
                        this.pagesArray = [];
                        if (this.currentPage >= 3)
                            this.pagesArray.push(1, '..', this.currentPage - 1, this.currentPage, this.currentPage + 1, '..', this.pages);
                        else if (this.currentPage === 3)
                            this.pagesArray.push(1, this.currentPage - 1, this.currentPage, this.currentPage + 1, '..', this.pages);
                        else
                            this.pagesArray.push(this.currentPage - 1, this.currentPage, this.currentPage + 1, '..', this.pages);
                    }
                } else {
                    this.pagesArray = [];
                    for (let i = 1; i < this.pages + 1; i++)
                        this.pagesArray.push(i);
                }
                this.displayGridData = this.allGridData.slice((this.currentPage - 1) * this.countEntriesPerPage, ((this.currentPage - 1) * this.countEntriesPerPage) + this.countEntriesPerPage);
            }
        }
    },
    template: `
            <div class="grid-table-container">
                <table class="pb-table">
                    <thead>
                        <pb-table-row>
                            <slot name="table-columns">
                            </slot>
                            <slot name="toolbox-header"></slot>
                        </pb-table-row>
                    </thead>
                    <tbody>
                        <pb-table-row v-for="(entry, index) in displayGridData" :class="tableRowClassName(entry, index)" :key="entry.id">
                            <pb-table-column v-if="entry[key] !== undefined" v-for="key in headerColumns" :key="key + '_' + entry.id">
                                {{entry[key]}}
                            </pb-table-column>
                            
                            <slot name="toolbox-rows" :entry="entry"></slot>
                        </pb-table-row>
                    </tbody>
                </table>
                <div class="table-pagination">
                    <button class="standard-button" v-for="page in pagesArray" :key="page" @click="setPage(page)">{{page}}</button>
                </div>
            </div>
    `
});


Vue.component('pb-table-row', {
    template: `
        <tr class="pb-tr">
            <slot></slot>
        </tr>
    `
})

Vue.component('pb-table-column', {
    template: `
        <td class="pb-td">
            <slot></slot>
        </td>
    `
})

Vue.component('pb-table-header-column', {
    props: {
        label: {
            type: String,
            required: false,
            default: ''
        },
        property: {
            type: String,
            required: false,
            default: ''
        },
        sortable: {
            type: Boolean,
            required: false,
            default: false
        },
        width: {
            type: String,
            required: false,
            default: ''
        },
        "sort-order": {
            type: String,
            required: false
        }
    },
    data() {
        return {
            order: 'pb-table-column-ascending',
            isActive: false,
            countOfSortClicks: 1,
            sortIconEnabled: true,
        }
    },
    mounted() {
        store.commit('addHeaderColumn', this.property)
        if (this.sortOrder !== undefined) {
            store.commit('setSortKey', this.property)
            switch (this.sortOrder) {
                case "descending":
                    this.order = 'pb-table-column-descending';
                    store.commit('setSortOrder', -1)
                    break;
                case "ascending":
                    this.order = 'pb-table-column-ascending';
                    store.commit('setSortOrder', 1)
                    break;
                case "none":
                    this.order = '';
                    store.commit('setSortOrder', 0)
                    break;
            }
        }
    },
    computed: {
        getWidth() {
            if (this.width === '') {
                this.$emit('update:width', 'auto');
            }

            return this.width
        }
    },
    methods: {
        sort() {
            if (this.sortable) {
                this.isActive = true;
                if (this.sortIconEnabled) {
                    if (this.sortable) {
                        store.commit('setSortKey', this.property)
                    }
                    if (this.order === 'pb-table-column-ascending') {
                        this.order = 'pb-table-column-descending'
                        store.commit('setSortOrder', 1)
                        this.countOfSortClicks++;
                    } else {
                        this.order = 'pb-table-column-ascending';
                        store.commit('setSortOrder', -1)
                        this.countOfSortClicks++;
                    }
                    if (this.countOfSortClicks === 2) {
                        this.countOfSortClicks = 0;
                        this.sortIconEnabled = false;
                    }
                } else {
                    this.order = '';
                    this.sortIconEnabled = true;
                    this.isActive = false;
                    store.commit('setSortOrder', 0)
                }
            }
        }

    },
    template: `
        <th class="pb-th" :class="{active: isActive}" :style="{ width: getWidth }" @click="sort()" >
            <div class="table-cell">
                {{ label }}
                <span v-if="sortable" class="pb-table-column-sortable-icon" :class="order"></span>
            </div>
        </th>
    `
})