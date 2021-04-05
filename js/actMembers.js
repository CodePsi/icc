var app = new Vue({
    el: '#app',
    data: {
        showNewEntryModal: false,
        showEditEntryModal: false,
        showDeleteEntryModal: false,
        showCommitteeDateModal: false,
        activeId: -1,
        searchQuery: '',
        data: [],
        employees: [],
        employeeId: '',
        position: '',
        date: '',
        actType: '',
        appointmentDate: '',
        positionOptions: [
            {
                value: 'Матеріально відповідальна',
                label: 'Матеріально відповідальна'
            },
            {
                value: 'Голова Комісії',
                label: 'Голова Комісії'
            },
            {
                value: 'Заст. гол. комісії',
                label: 'Заст. гол. комісії'
            },
            {
                value: 'Член Комісії',
                label: 'Член Комісії'
            }
        ],
        actTypeOptions: [
            {
                value: 'Акти установки',
                label: 'Акти установки'
            },
            {
                value: 'Акти списання',
                label: 'Акти списання'
            }
        ],
        employeesOptions: []
    },
    created() {
        this.loadAllEmployees();
    },
    methods: {
        async loadAllEmployees() {
            let response = await fetchGet('/icc/employees/')
            this.employees = await response.json();
            this.employeesOptions = convertEmployeeToSelectOptionsData(this.employees);
            this.loadActMembers();
        },
        async loadActMembers() {
            let response = await fetchGet('/icc/actMembers/');
            let result = await response.json();
            result.forEach(value => {
                let employee = app.employees.find(v => value.employeeId === v.id);
                if (employee !== undefined) {
                    value.employee = employee.surname + ' ' + employee.name + ' ' + employee.patronymic;
                } else {
                    value.employee = 'Помилка'
                }
            });
            this.data = result;
        },
        loadCommitteeDate() {
            // var request = new HttpRequest();
            // request.xmlHttpRequestInstance.onreadystatechange = function () {
            //
            // };
            //
            // request.sendGETRequest()
        },
        async addNewActMember() {
            this.showNewEntryModal = false;
            const data = {
                'employeeId': this.employeeId,
                'position': this.position,
                'actType': this.actType
            };
            let response = await fetchPost('/icc/actMembers/', data);
            console.log(await response.text())
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Запис успішно додано',
                showConfirmButton: false,
                timer: 1500
            })
            this.loadActMembers();
        },
        async updateActMember() {
            const data = {
                'employeeId': this.employeeId,
                'position': this.position,
                'actType': this.actType
            };
            await fetchPut(`/icc/actMembers/${this.activeId}`, data)
            app.showEditEntryModal = false;
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Запис успішно відредаговано',
                showConfirmButton: false,
                timer: 1500
            })
            this.loadActMembers();

        },
        async deleteActMember() {
            this.showDeleteEntryModal = false;
            await fetchDelete(`/icc/actMembers/${this.activeId}`);
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Запис успішно видалено',
                showConfirmButton: false,
                timer: 1500
            })
            this.loadActMembers();
        },
        async addDateToCommittee() {
            this.showCommitteeDateModal = false;
            const data = {
                'date': this.date
            };
            await fetchPut('/icc/actMembers/date', data);
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Дату додано',
                showConfirmButton: false,
                timer: 1500
            })
        },
        openNewEntryModalWindow() {
            this.showNewEntryModal = true;
            this.employee = '';
            this.position = '';
            this.actType = '';
        },
        openCommitteeDateModalWindow: function () {
            this.showCommitteeDateModal = true;
            this.date = new Date().toISOString().slice(0, 10);
        },
        async openEditEntryModalWindow(entryId) {
            this.showEditEntryModal = true;
            this.activeId = entryId;
            let response = await fetchGet(`/icc/actMembers/${entryId}`);
            let result = await response.json();
            this.employeeId = result.employeeId;
            this.position = result.actPosition;
            this.actType = result.actType;
        },
        async openDeleteEntryModalWindow(entryId) {
            this.activeId = entryId;
            this.showDeleteEntryModal = true;
        }
    }
});