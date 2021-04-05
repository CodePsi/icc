const app = new Vue({
    el: '#app',
    data: {
        showNewEntryModal: false,
        showEditEntryModal: false,
        showDeleteEntryModal: false,
        activeId: -1,
        searchQuery: '',
        gridColumns: ['id', 'наименування Предмета', 'тип', 'одиниці Вимірювання',
            'кількість', 'ціна', 'загальна Ціна', 'матеріально-відповідальна Особа',
            'інвентарний Номер'],
        data: [],
        employees: [],
        name: '',
        type: 'Росходний матеріал',
        unit: '',
        amount: 0,
        price: 0,
        responsible: '1',
        code: '',
        unitOptions: [
            {
                value: 'Немає',
                label: 'Немає'
            },
            {
                value: 'шт.',
                label: 'шт.'
            },
            {
                value: 'метри',
                label: 'метри'
            }
        ],
        responsibleEmployeesOptions: [],
        typeOptions: [
            {
                value: 'Росходний матеріал',
                label: 'Росходний матеріал'
            },
            {
                value: 'Запасна частина',
                label: 'Запасна частина'
            }
        ],
        showHidden: false,
        stockItems: []
    },
    computed: {
        totalSum() {
            return this.decimalAdjust('round', this.amount * this.price, -2);
        }
    },
    watch: {
        showHidden() {
            if (this.showHidden) {
                this.data = this.stockItems
            } else {
                let temp = this.stockItems
                temp = temp.filter(value => {
                    return value.amount > 0
                })
                this.data = temp
            }
        }
    },
    async created() {
        await this.getAllEmployees();
        await this.loadStockItems();
    },
    methods: {
        async loadStockItems() {
            let response = await fetchGet('/icc/stock/');

            let result = await response.json();
            this.stockItems = result;
            result.forEach(value => {
                let responsible = app.employees.find(v => v.id === value.responsiblePerson);
                if (responsible !== undefined) {
                    value.responsiblePerson = responsible.surname + ' ' + responsible.name + ' ' + responsible.patronymic
                } else {
                    value.responsiblePerson = '';
                }
            });
            result = result.filter(value => {
                return value.amount > 0
            });

            this.data = result;
        },
        async getAllEmployees() {
            let response = await fetchGet('/icc/employees/?responsible=1')
            this.employees = await response.json();
            this.responsibleEmployeesOptions = convertEmployeeToSelectOptionsData(this.employees);
        },
        async addNewStockItem() {
            this.showNewEntryModal = false;
            const data = {
                'itemName': this.name,
                'type': this.type,
                'unit': this.unit,
                'amount': this.amount,
                'price': this.price,
                'total': this.totalSum,
                'responsible': this.responsible,
                'code': this.code
            };
            await fetchPost('/icc/stock/', data);
            this.loadStockItems();
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Запис успішно додано',
                showConfirmButton: false,
                timer: 1500
            })
        },
        async updateStockItem() {
            this.showEditEntryModal = false;
            const data = {
                'itemName': this.name,
                'type': this.type,
                'unit': this.unit,
                'amount': this.amount,
                'price': this.price,
                'total': this.totalSum,
                'responsible': this.responsible,
                'code': this.code
            };
            await fetchPut(`/icc/stock/${this.activeId}`, data)
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Запис успішно оновлено',
                showConfirmButton: false,
                timer: 1500
            })
            this.loadStockItems();
        },
        async deleteStockItem() {
            this.showDeleteEntryModal = false;
            await fetchDelete(`/icc/stock/${this.activeId}`)
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Запис успішно видалено',
                showConfirmButton: false,
                timer: 1500
            });
            this.loadStockItems();
        },
        openNewEntryModalWindow() {
            this.showNewEntryModal = true;
            this.name = '';
            this.type = 'Росходний матеріал';
            this.unit = '';
            this.amount = 0;
            this.price = 0;
            this.responsible = '';
            this.code = '';
        },
        async openEditEntryModalWindow(entryId) {
            this.activeId = entryId;
            this.showEditEntryModal = true;
            let response = await fetchGet(`/icc/stock/${entryId}`);
            let result = await response.json();
            console.log(result);
            this.name = result.itemName;
            this.type = result.type;
            this.unit = result.unit;
            this.amount = result.amount;
            this.price = result.price;
            this.responsible = result.responsiblePerson;
            this.code = result.code;
        },
        async openDeleteEntryModalWindow(entryId) {
            this.activeId = entryId;
            this.showDeleteEntryModal = true;
        },
        decimalAdjust: function(type, value, exp) {
            // Если степень не определена, либо равна нулю...
            if (typeof exp === 'undefined' || +exp === 0) {
                return Math[type](value);
            }
            value = +value;
            exp = +exp;
            // Если значение не является числом, либо степень не является целым числом...
            if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0)) {
                return NaN;
            }
            // Сдвиг разрядов
            value = value.toString().split('e');
            value = Math[type](+(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp)));
            // Обратный сдвиг
            value = value.toString().split('e');
            return +(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp));
        }
    }
});