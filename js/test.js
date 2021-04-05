let app = new Vue({
    el: '#app',

    data: {
        tableData: [],
        tableColumns: ['id', 'test', 'anotherValue', 'otherV1', 'otherV2', 'otherV3', 'otherV4'],
        searchQuery: '',
        modal: false,
        options: [{
            value: 'Option1',
            label: 'Option1'
        }, {
            value: 'Option2',
            label: 'Option2'
        }, {
            value: 'Option3',
            label: 'Option3'
        }, {
            value: 'Option4',
            label: 'Option4'
        }, {
            value: 'Option5',
            label: 'Option5'
        }],
        test: '',
        activeId: 0
    },
    created: function() {
        let i = 0;
        this.tableData.push({'id': i, test: 'value' + i++, anotherValue: 'A lots of different text tex tex asd aqeqw eqwsa asd asd asdqwdqweqdas fsdafsefef' + i++, otherV1: 'Te dfjsldf werwe rweqwe qwre', otherV2: 'Te dfjsldf werwe rweqwe qwre', otherV3: 'Te dfjsldf werwe rweqwe qwre', otherV4: 'Te dfjsldf werwe rweqwe qwre' })
        this.tableData.push({'id': i, test: 'value' + i++, anotherValue: 'A lots of different text tex tex asd aqeqw eqwsa asd asd asdqwdqweqdas fsdafsefef' + i++, otherV1: 'Te dfjsldf werwe rweqwe qwre', otherV2: 'Te dfjsldf werwe rweqwe qwre', otherV3: 'Te dfjsldf werwe rweqwe qwre', otherV4: 'Te dfjsldf werwe rweqwe qwre' })
        this.tableData.push({'id': i, test: 'value' + i++, anotherValue: 'A lots of different text tex tex asd aqeqw eqwsa asd asd asdqwdqweqdas fsdafsefef' + i++, otherV1: 'Te dfjsldf werwe rweqwe qwre', otherV2: 'Te dfjsldf werwe rweqwe qwre', otherV3: 'Te dfjsldf werwe rweqwe qwre', otherV4: 'Te dfjsldf werwe rweqwe qwre' })
        this.tableData.push({'id': i, test: 'value' + i++, anotherValue: 'A lots of different text tex tex asd aqeqw eqwsa asd asd asdqwdqweqdas fsdafsefef' + i++, otherV1: 'Te dfjsldf werwe rweqwe qwre', otherV2: 'Te dfjsldf werwe rweqwe qwre', otherV3: 'Te dfjsldf werwe rweqwe qwre', otherV4: 'Te dfjsldf werwe rweqwe qwre' })
        this.tableData.push({'id': i, test: 'value' + i++, anotherValue: 'A lots of different text tex tex asd aqeqw eqwsa asd asd asdqwdqweqdas fsdafsefef' + i++, otherV1: 'Te dfjsldf werwe rweqwe qwre', otherV2: 'Te dfjsldf werwe rweqwe qwre', otherV3: 'Te dfjsldf werwe rweqwe qwre', otherV4: 'Te dfjsldf werwe rweqwe qwre' })
        this.tableData.push({'id': i, test: 'value' + i++, anotherValue: 'A lots of different text tex tex asd aqeqw eqwsa asd asd asdqwdqweqdas fsdafsefef' + i++, otherV1: 'Te dfjsldf werwe rweqwe qwre', otherV2: 'Te dfjsldf werwe rweqwe qwre', otherV3: 'Te dfjsldf werwe rweqwe qwre', otherV4: 'Te dfjsldf werwe rweqwe qwre' })
        this.tableData.push({'id': i, test: 'value' + i++, anotherValue: 'A lots of different text tex tex asd aqeqw eqwsa asd asd asdqwdqweqdas fsdafsefef' + i++, otherV1: 'Te dfjsldf werwe rweqwe qwre', otherV2: 'Te dfjsldf werwe rweqwe qwre', otherV3: 'Te dfjsldf werwe rweqwe qwre', otherV4: 'Te dfjsldf werwe rweqwe qwre' })
        this.tableData.push({'id': i, test: 'value' + i++, anotherValue: 'A lots of different text tex tex asd aqeqw eqwsa asd asd asdqwdqweqdas fsdafsefef' + i++, otherV1: 'Te dfjsldf werwe rweqwe qwre', otherV2: 'Te dfjsldf werwe rweqwe qwre', otherV3: 'Te dfjsldf werwe rweqwe qwre', otherV4: 'Te dfjsldf werwe rweqwe qwre' })
        this.tableData.push({'id': i, test: 'value' + i++, anotherValue: 'A lots of different text tex tex asd aqeqw eqwsa asd asd asdqwdqweqdas fsdafsefef' + i++, otherV1: 'Te dfjsldf werwe rweqwe qwre', otherV2: 'Te dfjsldf werwe rweqwe qwre', otherV3: 'Te dfjsldf werwe rweqwe qwre', otherV4: 'Te dfjsldf werwe rweqwe qwre' })
        this.tableData.push({'id': i, test: 'value' + i++, anotherValue: 'A lots of different text tex tex asd aqeqw eqwsa asd asd asdqwdqweqdas fsdafsefef' + i++, otherV1: 'Te dfjsldf werwe rweqwe qwre', otherV2: 'Te dfjsldf werwe rweqwe qwre', otherV3: 'Te dfjsldf werwe rweqwe qwre', otherV4: 'Te dfjsldf werwe rweqwe qwre' })
        this.tableData.push({'id': i, test: 'value' + i++, anotherValue: 'A lots of different text tex tex asd aqeqw eqwsa asd asd asdqwdqweqdas fsdafsefef' + i++, otherV1: 'Te dfjsldf werwe rweqwe qwre', otherV2: 'Te dfjsldf werwe rweqwe qwre', otherV3: 'Te dfjsldf werwe rweqwe qwre', otherV4: 'Te dfjsldf werwe rweqwe qwre' })
        this.tableData.push({'id': i, test: 'value' + i++, anotherValue: 'A lots of different text tex tex asd aqeqw eqwsa asd asd asdqwdqweqdas fsdafsefef' + i++, otherV1: 'Te dfjsldf werwe rweqwe qwre', otherV2: 'Te dfjsldf werwe rweqwe qwre', otherV3: 'Te dfjsldf werwe rweqwe qwre', otherV4: 'Te dfjsldf werwe rweqwe qwre' })
        this.tableData.push({'id': i, test: 'value' + i++, anotherValue: 'A lots of different text tex tex asd aqeqw eqwsa asd asd asdqwdqweqdas fsdafsefef' + i++, otherV1: 'Te dfjsldf werwe rweqwe qwre', otherV2: 'Te dfjsldf werwe rweqwe qwre', otherV3: 'Te dfjsldf werwe rweqwe qwre', otherV4: 'Te dfjsldf werwe rweqwe qwre' })
        this.tableData.push({'id': i, test: 'value' + i++, anotherValue: 'A lots of different text tex tex asd aqeqw eqwsa asd asd asdqwdqweqdas fsdafsefef' + i++, otherV1: 'Te dfjsldf werwe rweqwe qwre', otherV2: 'Te dfjsldf werwe rweqwe qwre', otherV3: 'Te dfjsldf werwe rweqwe qwre', otherV4: 'Te dfjsldf werwe rweqwe qwre' })
        this.tableData.push({'id': i, test: 'value' + i++, anotherValue: 'A lots of different text tex tex asd aqeqw eqwsa asd asd asdqwdqweqdas fsdafsefef' + i++, otherV1: 'Te dfjsldf werwe rweqwe qwre', otherV2: 'Te dfjsldf werwe rweqwe qwre', otherV3: 'Te dfjsldf werwe rweqwe qwre', otherV4: 'Te dfjsldf werwe rweqwe qwre' })

    },
    methods: {
        openModalWindow: function (id) {
            this.modal = true;
            this.activeId = id;
        },
        determineRowClassName(row, index) {
            if (index === 2)
                return 'table-color'

            return ''
        }

    }
});