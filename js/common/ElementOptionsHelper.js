function convertEmployeeToSelectOptionsData(employees) {
    let employeeOptions = []
    employees.forEach(value => {
        let label = value.surname + ' ' + value.name + ' ' + value.patronymic;
        let employeeId = value.id;
        employeeOptions.push({
            value: employeeId,
            label: label
        })
    });

    return employeeOptions;
}