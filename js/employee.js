let app = new Vue({
    el: '#app',
    data: {
        showNewEntryModal: false,
        showEditEntryModal: false,
        showDeleteEntryModal: false,
        activeModalValue: -1,
        searchQuery: '',
        gridColumns: ['id', 'прізвище', 'ім\'я', 'по-батькові', 'статус Знаходження', 'контактний Номер', 'посада', 'матеріально-відповідальний'],
        data: [],
        employees: [],
        name: '',
        surname: '',
        patronymic: '',
        status: 'Глобальний (університет)',
        contactNumber: '',
        position: '',
        responsible: 'Так',
        activeId: -1,
        employeesStatusOptions: [
            {
                value: 'Локально (ІСС)',
                label: 'Локально (ІСС)'
            },
            {
                value: 'Глобальний (університет)',
                label: 'Глобальний (університет)'
            },
            {
                value: 'Начальник',
                label: 'Начальник'
            }
        ],
        responsibleOptions: [
            {
                label: 'Так',
                value: 1
            },
            {
                label: 'Ні',
                value: 0
            }
        ]
    },
    created() {
        this.loadEmployees();
    },
    methods: {
        async loadEmployees() {
            let response = await fetch('/icc/employees/', {
                method: 'GET'
            })

            let result = await response.json();
            result.forEach(value => {
                if (value.responsible === 1) {
                    value.responsible = 'Так'
                } else {
                    value.responsible = 'Ні'
                }
            });
            this.data = result;
            // var request = new HttpRequest();
            // request.xmlHttpRequestInstance.onreadystatechange = function (ev) {
            //     if (request.isRequestSuccessful()) {
            //         var employeesJSON = JSON.parse(request.xmlHttpRequestInstance.responseText);
            //         for (var i = 0; i < employeesJSON.length; i++) {
            //             if (employeesJSON[i][7] === '1') employeesJSON[i][7] = 'Так';
            //             else employeesJSON[i][7] = 'Ні';
            //             employeesJSON[i] = {
            //                 'id': employeesJSON[i][0],
            //                 'ім\'я': employeesJSON[i][1],
            //                 'прізвище': employeesJSON[i][2],
            //                 'по-батькові': employeesJSON[i][3],
            //                 'статус Знаходження': employeesJSON[i][4],
            //                 'контактний Номер': employeesJSON[i][5],
            //                 'посада': employeesJSON[i][6],
            //                 'матеріально-відповідальний': employeesJSON[i][7]
            //             };
            //         }
            //
            //         app.gridData = employeesJSON;
            //         app.setUpPagination();
            //     }
            // };
            // request.sendGETRequest("/icc/employees/", "");
        },
        async addNewEmployee() {
            const data = {
                'name': this.name,
                'surname': this.surname,
                'patronymic': this.patronymic,
                'status': this.status,
                'contactNumber': this.contactNumber,
                'position': this.position,
                'responsible': this.responsible
            };
            await fetchPost('/icc/employees/', data);
            this.showNewEntryModal = false;
            this.loadEmployees();
            // var request = new HttpRequest();
            // request.xmlHttpRequestInstance.onreadystatechange = function (ev) {
            //     if (request.isRequestSuccessful()) {
            //         console.log('Status: ' + request.xmlHttpRequestInstance.responseText);
            //         app.loadEmployeesEntries();
            //         app.showNewEntryModal = false;

            //     }
            // };
            //
            // request.sendPOSTRequest("/icc/employees/", JSON.stringify({'name': app.name, 'surname': app.surname, 'patronymic': app.patronymic, 'status': app.status, 'contactNumber': app.contactNumber, 'position': app.position, 'responsible': app.responsible}));
        },
        async updateEmployee() {
            this.showEditEntryModal = false;
            const data = {
                'name': this.name,
                'surname': this.surname,
                'patronymic': this.patronymic,
                'status': this.status,
                'contactNumber': this.contactNumber,
                'position': this.position,
                'responsible': this.responsible
            };
            let response = await fetchPut(`/icc/employees/${this.activeId}`, data);
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Запис успішно відредаговано',
                showConfirmButton: false,
                timer: 1500
            });

            this.loadEmployees();
            // var request = new HttpRequest();
            // request.xmlHttpRequestInstance.onreadystatechange = function (ev) {
            //     if (request.isRequestSuccessful()) {
            //         console.log('Status: ' + request.xmlHttpRequestInstance.responseText);
            //         app.loadEmployeesEntries();
            //         app.showEditEntryModal = false;

            //     }
            // };
            // var responsible = app.responsible === 'Так' ? 1 : 0;
            // request.sendPUTRequest("/icc/employees/" + this.activeModalValue, JSON.stringify({'name': app.name, 'surname': app.surname, 'patronymic': app.patronymic, 'status': app.status, 'contactNumber': app.contactNumber, 'position': app.position, 'responsible': responsible}));

        },
        async deleteEmployee() {
            await fetchDelete(`/icc/employees/${this.activeId}`)
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Запис успішно видалено',
                showConfirmButton: false,
                timer: 1500
            })

            this.loadEmployees()
            // var request = new HttpRequest();
            // request.xmlHttpRequestInstance.onreadystatechange = function (ev) {
            //     if (request.isRequestSuccessful()) {
            //         console.log("Status: " + request.xmlHttpRequestInstance.responseText);
            //         app.loadEmployeesEntries();
            //         app.showDeleteEntryModal = false;

            //     }
            // };
            // request.sendDELETERequest("/icc/employees/" + this.activeModalValue, "");
        },
        showNewEntryModalWindow: function () {
            app.showNewEntryModal = true;
            app.name = '';
            app.surname = '';
            app.patronymic = '';
            app.status = '';
            app.contactNumber = '';
            app.position = '';
            app.responsible = '';
        },
        async openEditEntryModal(entryId) {
            let response = await fetchGet(`/icc/employees/${entryId}`)
            // console.log(await response.text())
            let result = await response.json();
            console.log(result)
            this.showEditEntryModal = true;
            this.activeId = entryId;
            this.name = result.name;
            this.surname = result.surname;
            this.patronymic = result.patronymic;
            this.status = result.status;
            this.contactNumber = result.contactNumber;
            this.position = result.position;
            this.responsible = result.responsible;
        },
        async openDeleteEntryModal(entryId) {
            this.activeId = entryId;
            this.showDeleteEntryModal = true;
        }
    }
});