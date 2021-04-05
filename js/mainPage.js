const app = new Vue({
    el: '#app',
    methods: {
        openPage: function (uri) {
            switch (uri) {
                case 'sql-executor':
                    window.location.href = '/icc/admin/sql-executor';

            }
        }
    }

});