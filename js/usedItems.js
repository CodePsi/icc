const app = new Vue({
    el: '#app',
    data: {
        searchQuery: '',
        data: [],
        activeId: '',
        request: '',
        item: '',
        count: '',
        date: '',
        inventoryNumber: ''
    },
    created() {
        this.loadUsedItems();
    },
    methods: {
        async loadUsedItems() {
            let response = await fetchGet('/icc/usedItems/');
            this.data = await response.json();
        },
    }
});