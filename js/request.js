let app = new Vue({
    el: '#app',
    data: {
        showModal: false,
        showNewEntryModal: false,
        showPrintModal: false,
        showEditEntryModal: false,
        showCloseEntryModal: false,
        showDeleteEntryModal: false,
        activeModalValue: -1,
        searchQuery: '',
        data: [],
        employees: [],
        date: '',
        employee: '',
        building: '',
        auditorium: '',
        reason: '',
        status: '',
        textBoxes: [],
        selects: [],
        usedItems: [],
        activeId: -1,
        currentCloseRequestId: -1,
        previousCloseRequestId: -1,
        inventoryNumbers: [],
        stockItems: [],
        usedItemsModels: [],
        model: '',
        technicalTicketNeeded: 'Ні',
        employeeOptions: [],
        stockItemsOptions: [],
        buildingOptions: [
            {
                label: "Корпуси",
                options: [
                    {
                        value: 'Головний',
                        label: 'Головний'
                    },
                    {
                        value: 'Гуманітарний',
                        label: 'Гуманітарний'
                    },
                    {
                        value: 'ДТО',
                        label: 'ДТО'
                    },
                    {
                        value: 'Худ. граф.',
                        label: 'Худ. граф.'
                    },
                    {
                        value: 'Муз. пед.',
                        label: 'Муз. пед.'
                    }],
            },
            {
                label: "Гуртожитки",
                options: [
                    {
                        value: 'Гуртожиток #1',
                        label: 'Гуртожиток #1'
                    },
                    {
                        value: 'Гуртожиток #2',
                        label: 'Гуртожиток #2'
                    },
                    {
                        value: 'Гуртожиток #3',
                        label: 'Гуртожиток #3'
                    },
                    {
                        value: 'Гуртожиток #4',
                        label: 'Гуртожиток #4'
                    }]
            },
            {
                label: 'Інші будівлі',
                options: [
                    {
                        value: 'Віварій',
                        label: 'Віварій'
                    },
                    {
                        value: 'Майстерня',
                        label: 'Майстерня'
                    }]
            }],
        technicalTicketOptions: [
            {
                value: 'Так',
                label: true
            },
            {
                value: 'Ні',
                label: false
            },
        ]
    },
    computed: {
        chief () {
            // await this.loadAllEmployees(); //For updating app.employees variable
            for (let i = 0; i < this.employees.length; i++) {
                if (this.employees[i]['status'] === 'Начальник') {
                    return app.employees[i]['name'] + ' ' + app.employees[i]['surname'];
                }
            }

            return 'None';
        },
        requestCompletion: function (status) {
            return {
                'unfinished-request': status === 'Відкрита'
            }
        }

    },
    async mounted() {
        await this.loadAllRequests();
        this.date = this.getNow();
    },
    methods: {
        async loadAllRequests() {
            await this.loadAllEmployees();
            let response = await fetch('/icc/requests/', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json;charset=utf-8'
                }
            });
            let result = await response.json();
            result.forEach((value) => {
                if (value.status)
                    value.status = 'Закрита';
                else
                    value.status = 'Відкрита';

                let employeeData = app.employees.filter((employee) => { return employee.id === parseInt(value.employeeId) })[0];
                let employee = '';
                if (employeeData !== undefined) {
                    employee = employeeData.surname + ' ' + employeeData.name + ' ' + employeeData.patronymic;
                }

                value.employee = employee;
            });
            this.data = result;
        },
        async loadAllEmployees() {
            let response = await fetch('/icc/employees/', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json;charset=utf-8'
                }
            });

            this.employees = await response.json();
            this.convertEmployeeToSelectOptionsData();
        },
        async openEditEntryModal(entry) {
            let response = await fetch('/icc/requests/' + entry.id, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json;charset=utf-8'
                }
            });

            let result = await response.json();
            this.showEditEntryModal = true;
            this.activeId = entry.id;
            this.employee = result.employeeId;
            this.building = result.building;
            this.auditorium = result.auditorium;
            this.reason = result.reason;
            this.status = result.status;
            this.date = result.date.replace(' ', 'T');
            this.technicalTicketNeeded = result.technicalTicketNeeded;
        },
        async openCloseRequestModal(entryId) {
            this.showCloseEntryModal = true;
            this.activeId = entryId;
            if (this.activeId !== this.previousCloseRequestId) {
                this.usedItemsModels = [];
                this.usedItems = [];
            }
            await this.loadStockItems();
        },
        async loadStockItems() {
            let response = await fetch('/icc/stock/', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json;charset=utf-8'
                }
            });
            this.stockItems = await response.json();
            this.convertStockItemsToSelectOptionsData();
        },
        async addNewRequest() {
            const content = {
                'date': this.date,
                'employee': this.employee,
                'building': this.building,
                'auditorium': this.auditorium,
                'reason': this.reason,
                'status': false,
                'technicalTicketNeeded': this.technicalTicketNeeded
            };
            let response = await fetchPost('/icc/requests/', content)
            if (response.status === 200) {
                this.showNewEntryModal = false;
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Заявка успішно додано',
                    showConfirmButton: false,
                    timer: 1500
                })
            }

            await app.loadAllRequests();

        },
        onRequestWindowCloses() {
            this.previousCloseRequestId = this.activeId;
        },
        convertEmployeeToSelectOptionsData() {
            this.employeeOptions = []
            this.employees.forEach(value => {
                let label = value.surname + ' ' + value.name + ' ' + value.patronymic;
                let employeeId = value.id;
                this.employeeOptions.push({
                    value: employeeId,
                    label: label
                })
            });
        },
        convertStockItemsToSelectOptionsData() {
            this.stockItemsOptions = []
            this.stockItems.forEach(value => {
                let label = value.itemName;
                let itemId = value.id;
                this.stockItemsOptions.push({
                    value: itemId,
                    label: label
                });

            });
        },
        openNewEntryRequestWindow() {
            this.showNewEntryModal = true;
            this.employee = '';
            this.building = '';
            this.auditorium = '';
            this.reason = '';
            this.date = this.getNow();
            this.technicalTicketNeeded = false;
        },
        openDeleteRequestModal(entryId) {
            this.activeId = entryId;
            this.showDeleteEntryModal = true;
        },
        openPrintRequestPage(entryId) {
            window.open('/icc/requests/view/pdf/' + entryId, '_blank')
        },
        cleanUpData() {
            this.employee = '';
            this.building = '';
            this.auditorium = '';
            this.reason = '';
            this.status = '';
            this.date = '';
        },
        async updateRequest() {
            this.showEditEntryModal = false;
            let status = this.status;
            const content = {
                'date': this.date,
                'employee': this.employee,
                'building': this.building,
                'auditorium': this.auditorium,
                'reason': this.reason,
                'status': this.status,
                'technicalTicketNeeded': this.technicalTicketNeeded
            };
            let response = await fetchPut(`/icc/requests/${this.activeId}`, content)
            console.log(await response.text())
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Запис успішно оновлено',
                showConfirmButton: false,
                timer: 1500
            })
            await app.loadAllRequests();
        },
        async closeRequest() {
            this.showCloseEntryModal = false;
            const data = {"usedItems": [], "requestId": this.activeId};
            for (let i = 0; i < this.usedItems.length; i++) {
                const itemId = this.$refs[this.usedItems[i]['option']][0].value;
                const countOfElements = this.$refs[this.usedItems[i]['input']][0].value;
                const inventoryNumber = this.$refs[this.usedItems[i]['inventoryNumber']][0].value;
                data["usedItems"].push({ 'itemId': itemId, 'count': countOfElements, 'inventoryNumber': inventoryNumber });
            }
            await fetchPost(`/icc/requests/close/${this.activeId}`, data);

            await this.loadAllRequests();

        },
        addInputBox: function () {
            this.usedItems.push({'option': 'opt' + this.usedItems.length, 'input': 'in' + this.usedItems.length, 'inventoryNumber': 'invnum' + this.usedItems});
        },
        addInventoryNumberField: function () {
            this.inventoryNumbers.push({'inventoryNumber': 'invnum' + this.inventoryNumbers.length});
        },
        async deleteRequest() {
            this.showDeleteEntryModal = false;
            await fetchDelete(`/icc/requests/${this.activeId}`)
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Запис успішно видалено',
                showConfirmButton: false,
                timer: 1500
            })

            await this.loadAllRequests();
        },
        getNow() {
            const d = new Date();
            return d.getFullYear() + '-' + this.fixDate(d.getMonth() + 1) + '-' + this.fixDate(d.getDate()) + '\T' + this.fixDate(d.getHours()) + ':' + this.fixDate(d.getMinutes());
        },
        fixDate(date) {
            if (date < 10) {
                return '0' + date;
            } else {
                return date;
            }
        },
        convertDateToDateTimeFormat(date) {

        },
        determineTableRowClassName(row, index) {
            if (row.status === 'Відкрита') {
                return 'unfinished-request';
            }

            return '';
        }
    }
});
