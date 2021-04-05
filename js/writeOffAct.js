const app = new Vue({
    el: '#app',
    data: {
        showNewEntryModal: false,
        showEditEntryModal: false,
        showDeleteEntryModal: false,
        activeId: -1,
        searchQuery: '',
        gridColumns: ['id', 'дата Початку', 'дата Кінця'],
        data: [],
        gridDataAll: [],
        startDate: '1950-01-01',
        endDate: '1950-01-01',
        pdf: '',
        writeOffActs: [],
        employees: [],
        employeesOptions: [],
        responsiblePerson: ''
    },
    async created() {
        await this.loadWriteOffActs();
    },
    methods: {
        async loadWriteOffActs() {
            await this.loadAllEmployees();
            let response = await fetchGet('/icc/writeOffActs/');
            let result = await response.json();
            result.forEach(value => {
                let employee = app.employees.find(v => value.responsiblePerson === v.id);
                if (employee !== undefined) {
                    value.responsiblePerson = employee.surname + ' ' + employee.name + ' ' + employee.patronymic;
                } else {
                    value.responsiblePerson = 'Не назначено'
                }
            })
            this.data = result;
        },
        async loadAllEmployees() {
            let response = await fetchGet('/icc/employees/')
            this.employees = await response.json();
            this.employeesOptions = convertEmployeeToSelectOptionsData(this.employees);
        },
        async addNewWriteOffAct() {
            this.showNewEntryModal = false;
            const data = {
                'startDate': this.startDate,
                'endDate': this.endDate,
                'responsiblePerson': this.responsiblePerson
            };
            await fetchPost('/icc/writeOffActs/', data);
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Запис успішно додано',
                showConfirmButton: false,
                timer: 1500
            })
            await this.loadWriteOffActs();

        },
        async updateWriteOffAct() {
            this.showEditEntryModal = false;
            const data = {
                'startDate': this.startDate,
                'endDate': this.endDate,
                'responsiblePerson': this.responsiblePerson
            };

            await fetchPut(`/icc/writeOffActs/${this.activeId}`, data);
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Запис успішно оновлено',
                showConfirmButton: false,
                timer: 1500
            })
            await this.loadWriteOffActs();
        },
        async deleteWriteOffAct() {
            this.showDeleteEntryModal = false;
            await fetchDelete(`/icc/writeOffActs/${this.activeId}`);
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Запис успішно видалено',
                showConfirmButton: false,
                timer: 1500
            })
            await this.loadWriteOffActs();
        },
        openNewEntryModalWindow: function () {
            this.showNewEntryModal = true;
            const nowDate = new Date().toISOString().slice(0, 10);
            this.startDate = nowDate;
            this.endDate = nowDate;
            this.responsiblePerson = '';
        },
        async openEditEntryModalWindow(entryId) {
            this.activeId = entryId;
            this.showEditEntryModal = true;
            let response = await fetchGet(`/icc/writeOffActs/${entryId}`);
            let result = await response.json();
            this.startDate = result.startDate;
            this.endDate = result.endDate;
            this.responsiblePerson = result.responsiblePerson;
        },
        openDeleteEntryModalWindow(entryId) {
            this.activeId = entryId;
            this.showDeleteEntryModal = true;
        },
        openPrintWriteOffActTab(entryId) {
            window.open(`/icc/writeOffActs/view/pdf/${entryId}`, '_blank')
        }
    }
});
