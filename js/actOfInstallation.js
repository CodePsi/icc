var app = new Vue({
    el: '#app',
    data: {
        showNewEntryModal: false,
        showEditEntryModal: false,
        showDeleteEntryModal: false,
        activeId: -1,
        searchQuery: '',
        data: [],
        startDate: '',
        endDate: '',
        month: '',
        pdf: '',
        responsiblePerson: '',
        monthsOptions: [
            {
                value: 'Січень',
                label: 'Січень'
            },
            {
                value: 'Лютий',
                label: 'Лютий'
            },
            {
                value: 'Березень',
                label: 'Березень'
            },
            {
                value: 'Квітень',
                label: 'Квітень'
            },
            {
                value: 'Травень',
                label: 'Травень'
            },
            {
                value: 'Червень',
                label: 'Червень'
            },
            {
                value: 'Липень',
                label: 'Липень'
            },
            {
                value: 'Серпень',
                label: 'Серпень'
            },
            {
                value: 'Вересень',
                label: 'Вересень'
            },
            {
                value: 'Жовтень',
                label: 'Жовтень'
            },
            {
                value: 'Листопад',
                label: 'Листопад'
            },
            {
                value: 'Грудень',
                label: 'Грудень'
            }
        ],
        employeesOptions: []
    },
    async created() {
        await this.loadActsOfInstallation();
    },
    methods: {
        async loadActsOfInstallation() {
            await this.loadAllEmployees();
            let response = await fetchGet('/icc/actsOfInstallation/');
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
        async addActOfInstallation() {
            this.showNewEntryModal = false;
            const data = {
                'startDate': this.startDate,
                'endDate': this.endDate,
                'month': this.month,
                'responsiblePerson': this.responsiblePerson
            };

            await fetchPost('/icc/actsOfInstallation/', data);
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Запис успішно додано',
                showConfirmButton: false,
                timer: 1500
            })
            await this.loadActsOfInstallation();
        },
        async updateActOfInstallation() {
            this.showEditEntryModal = false;
            const data = {
                'startDate': this.startDate,
                'endDate': this.endDate,
                'month': this.month,
                'responsiblePerson': this.responsiblePerson
            };

            await fetchPut(`/icc/actsOfInstallation/${this.activeId}`, data);
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Запис успішно відредаговано',
                showConfirmButton: false,
                timer: 1500
            })
            await this.loadActsOfInstallation();
        },
        async deleteActOfInstallation() {
            this.showDeleteEntryModal = false;
            await fetchDelete(`/icc/actsOfInstallation/${this.activeId}`);
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Запис успішно видалено',
                showConfirmButton: false,
                timer: 1500
            })
            await this.loadActsOfInstallation();
        },
        openNewEntryModalWindow() {
            this.showNewEntryModal = true;
            const now = new Date();
            const nowDate = now.toISOString().slice(0, 10);
            const month = this.monthsOptions[now.getMonth()].label;
            this.startDate = nowDate;
            this.endDate = nowDate;
            this.month = month;
            this.responsiblePerson = '';
        },
        async openEditEntryModalWindow(entryId) {
            this.activeId = entryId;
            this.showEditEntryModal = true;
            let response = await fetchGet(`/icc/actsOfInstallation/${entryId}`);
            let result = await response.json();
            this.startDate = result.startDate;
            this.endDate = result.endDate;
            this.month = result.month;
            this.responsiblePerson = result.responsiblePerson;
        },
        openDeleteEntryModalWindow(entryId) {
            this.activeId = entryId;
            this.showDeleteEntryModal = true;
        },
        openPrintActOfInstallationTab(entryId) {
            window.open(`/icc/actsOfInstallation/view/pdf/${entryId}`, '_blank')
        }
    }
});