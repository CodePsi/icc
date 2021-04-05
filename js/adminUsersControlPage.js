Vue.component('icc-header', {
    template: '#icc-header',
    methods: {
        redirect: function(url) {
            window.location.href = url;
        }
    }
});

Vue.component('modal', {
    template: '#modal-template',
    props: {
        width: String
    },
    methods: {
        getActiveID: function() {
            return app.activeModalValue
        }
    }
});

var componentGrid = Vue.component('grid', {
    template: '#grid-template',
    props: {
        heroes: Array,
        columns: Array,
        filterKey: String
    },
    data: function () {
        var sortOrders = {};
        this.columns.forEach(function (key) {
            sortOrders[key] = 1
        });
        return {
            sortKey: this.columns[0],
            sortOrders: sortOrders,
            currentPage: 1,
            pages: 1,
            pagesArray: [],
            countEntriesEachPage: 10,
            displayGridData: [],
            allGridData: []
        }
    },
    created: function() {
        this.allGridData = this.heroes
        this.setUpPagination();
    },
    watch: {
        heroes: function () {
            this.allGridData = this.heroes
            this.setUpPagination()
        }
    },
    computed: {
        filteredHeroes: function () {
            var sortKey = this.sortKey;
            var filterKey = this.filterKey && this.filterKey.toLowerCase();
            var order = this.sortOrders[sortKey] || 1;
            this.allGridData = this.heroes;
            if (filterKey) {
                this.allGridData = this.allGridData.filter(function (row) {
                    return Object.keys(row).some(function (key) {
                        return String(row[key]).toLowerCase().indexOf(filterKey) > -1;
                    })
                })
            }
            if (sortKey) {
                this.allGridData = this.allGridData.slice().sort(function (a, b) {
                    a = a[sortKey];
                    b = b[sortKey];
                    return (a === b ? 0 : a > b ? 1 : -1) * order;
                })
            }
            return this.displayGridData
        }
    },
    filters: {
        capitalize: function (str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        }
    },
    methods: {
        sortBy: function (key) {
            this.sortKey = key;
            this.sortOrders[key] = this.sortOrders[key] * -1;
            this.setUpPagination();
        },
        setUpPagination: function () {
            this.filteredHeroes
            this.pages = Math.ceil(this.allGridData.length / this.countEntriesEachPage);
            this.setPage(1);
            this.displayGridData = this.allGridData.slice((this.currentPage - 1) * this.countEntriesEachPage, ((this.currentPage - 1) * this.countEntriesEachPage) + this.countEntriesEachPage);
        },
        setPage: function(page) {
            if (page !== '..') { //Case when we don't need to change page because it is only decoration.
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
                    for (var i = 1; i < this.pages + 1; i++)
                        this.pagesArray.push(i);
                }
                this.displayGridData = this.allGridData.slice((this.currentPage - 1) * this.countEntriesEachPage, ((this.currentPage - 1) * this.countEntriesEachPage) + this.countEntriesEachPage);
            }
        },
        displayEditUserModalWindow: function (value) {
            app.showEditEntryModal = true;
            app.activeModalValue = value;
            var request = new HttpRequest();
            request.xmlHttpRequestInstance.onreadystatechange = function() {
                if (request.isRequestSuccessful()) {
                    // console.log(request.xmlHttpRequestInstance.responseText)
                    let responseObject = JSON.parse(request.xmlHttpRequestInstance.responseText);
                    app.login = responseObject['login'];
                    app.userName = responseObject['name'];
                }
            };
            request.sendGETRequest("/icc/users/" + value, "");
        },
        displayChangeUserPasswordModalWindow: function (value) {
            app.showChangePasswordEntryModal = true;
            app.activeModalValue = value;
        },
        displayDeleteUserModalWindow: function (value) {
            app.showDeleteEntryModal = true;
            app.activeModalValue = value;
        }
    }
});

let app = new Vue({
    el: '#app',
    data: {
        login: '',
        password: '',
        userName: '',
        gridColumns: ['id', 'login', 'name'],
        gridData: [],
        searchQuery: '',
        showNewEntryModal: false,
        showEditEntryModal: false,
        showDeleteEntryModal: false,
        showChangePasswordEntryModal: false
    },
    created: function(){
        this.loadUsers();
    },
    methods: {
        loadUsers: function () {
            var request = new HttpRequest();

            request.xmlHttpRequestInstance.onreadystatechange = function () {
                if (request.isRequestSuccessful()) {
                    var json = JSON.parse(request.xmlHttpRequestInstance.responseText);
                    for (var i = 0; i < json.length; i++) {
                        json[i] = {
                            "id": json[i][0],
                            "login": json[i][1],
                            "name": json[i][3]
                        }

                        app.gridData = json;
                    }
                }
            };


            request.sendGETRequest("/icc/users/", "");
        },
        openNewEntryUserWindow: function () {
            this.login = '';
            this.password = '';
            this.name = '';
            this.showNewEntryModal = true;
        },
        closeNewEntryModalWindow: function () {
            var request = new HttpRequest();

            request.xmlHttpRequestInstance.onreadystatechange = function() {
                if (request.isRequestSuccessful()) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Запис успішно додано',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    app.loadUsers();
                    app.showNewEntryModal = false;
                }
            };

            request.sendPOSTRequest("/icc/register", JSON.stringify({'login': this.login, 'password': this.password, 'name': this.userName}));
        },
        changeUserPassword: function () {
            let request = new HttpRequest();
            request.xmlHttpRequestInstance.onreadystatechange = function() {
                if (request.isRequestSuccessful()) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Пароль змінено!',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    app.showChangePasswordEntryModal = false;
                }
            };
            request.sendPUTRequest("/icc/users/password/" + app.activeModalValue, JSON.stringify({"password": this.password}));
        },
        deleteUser: function () {
            let request = new HttpRequest();
            request.xmlHttpRequestInstance.onreadystatechange = function() {
                if (request.isRequestSuccessful()) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Запис успішно видалено',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    app.showDeleteEntryModal = false;
                }
            };
            request.sendDELETERequest("/icc/users/" + app.activeModalValue, "");
        },
        changeUser: function () {
            let request = new HttpRequest();
            request.xmlHttpRequestInstance.onreadystatechange = function() {
                if (request.isRequestSuccessful()) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Запис успішно відредаговано',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    app.showEditEntryModal = false;
                }
            };
            request.sendPUTRequest("/icc/users/" + value, JSON.stringify({'login': this.login, 'name': this.userName}));
        }
    }

});