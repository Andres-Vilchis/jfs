document.addEventListener('DOMContentLoaded', () => {

    document.querySelectorAll('table').forEach(table => {

        table.classList.add(
            'table',
            'table-hover',
            'table-striped',
            'table-sm',
            'mb-0'
        );

    });

});