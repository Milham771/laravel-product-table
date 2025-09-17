<!DOCTYPE html>
<html>
<head>
    <title>Product Table</title>
    <script src="https://cdn.jsdelivr.net/npm/ag-grid-community/dist/ag-grid-community.min.noStyle.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ag-grid-community/styles/ag-grid.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ag-grid-community/styles/ag-theme-alpine.css">
    <style>
        .ag-theme-alpine {
            height: 500px;
            width: 100%;
        }
    </style>
</head>
<body>
    <h1>Product Table</h1>
    <p>This is an example implementation of AG Grid. You can customize this view to work with any data structure.</p>
    <div id="myGrid" class="ag-theme-alpine"></div>

    <script>
        // Define column headers
        // This is just an example - users should modify this to match their data structure
        const columnDefs = [
            { headerName: 'ID', field: 'id', sortable: true, filter: true },
            { headerName: 'Name', field: 'name', sortable: true, filter: true },
            { headerName: 'Description', field: 'description', sortable: true, filter: true },
            { headerName: 'Price', field: 'price', sortable: true, filter: true },
            { headerName: 'Quantity', field: 'quantity', sortable: true, filter: true },
            { headerName: 'Created At', field: 'created_at', sortable: true, filter: true },
            { headerName: 'Updated At', field: 'updated_at', sortable: true, filter: true }
        ];

        // Initialize grid
        const gridOptions = {
            defaultColDef: {
                editable: true,
                sortable: true,
                filter: true
            },
            columnDefs: columnDefs,
            rowData: [], // Data will be loaded via API
            rowSelection: 'multiple',
            animateRows: true,
            pagination: true,
            paginationPageSize: 10
        };

        // Create grid
        const eGridDiv = document.querySelector('#myGrid');
        new agGrid.Grid(eGridDiv, gridOptions);

        // Fetch data from API
        // Users should modify this URL to match their API endpoint
        fetch('/api/products')
            .then(response => response.json())
            .then(data => {
                gridOptions.api.setRowData(data);
            });
    </script>
    
    <h2>How to Customize This Component</h2>
    <p>To use this component with your own data structure:</p>
    <ol>
        <li>Modify the <code>columnDefs</code> array to match the fields in your database table.</li>
        <li>Change the URL in the <code>fetch</code> call to point to your own API endpoint.</li>
        <li>Adjust the grid options as needed for your use case.</li>
        <li>Add any custom styling or additional features you require.</li>
    </ol>
</body>
</html>