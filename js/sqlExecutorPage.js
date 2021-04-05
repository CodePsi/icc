let app = new Vue({
    el: '#app',
    data: {
        sqlText: '',
        showResults: false,
        sqlExecutionResult: '',
    },
    methods: {
        async executeSql() {
            const response = await fetchPost('/icc/admin/sql-executor/execute', {"data": app.sqlText});
            this.sqlExecutionResult = await response.text();
            this.showResults = true;
        }
    }

});