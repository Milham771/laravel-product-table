<!DOCTYPE html>
<html>
<head>
    <title>Product Table</title>
    <script src="https://cdn.jsdelivr.net/npm/ag-grid-community/dist/ag-grid-community.min.noStyle.js"></script>

    <style>
        .ag-theme-alpine {
            height: 500px;
            width: 100%;
        }

        .toolbar {
            margin-bottom: 10px;
        }
        .toolbar button {
            padding: 8px 16px;
            margin-right: 10px;
            background-color: #2196F3;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .toolbar button.delete {
            background-color: #f44336;
        }

    </style>
</head>
<body>
    <h1>Product Table</h1>

    <p>This is an example implementation of AG Grid with CRUD operations. You can customize this view to work with any data structure.</p>
    
    <!-- Toolbar -->
    <div class="toolbar">
        <button id="addProductButton">Add New Product</button>
        <button id="deleteProductButton" class="delete">Delete Selected</button>
    </div>
    

    <div id="myGrid" class="ag-theme-alpine"></div>

    <script>
        const columnDefs = [
            { headerName: 'ID', field: 'id', sortable: true, filter: true, editable: false },
            { headerName: 'Name', field: 'name', sortable: true, filter: true, editable: true },
            { headerName: 'Description', field: 'description', sortable: true, filter: true, editable: true },
            { headerName: 'Price', field: 'price', sortable: true, filter: true, editable: true },
            { headerName: 'Quantity', field: 'quantity', sortable: true, filter: true, editable: true },
            { headerName: 'Created At', field: 'created_at', sortable: true, filter: true, editable: false },
            { headerName: 'Updated At', field: 'updated_at', sortable: true, filter: true, editable: false }
        ];

        const gridOptions = {
            defaultColDef: {
                editable: true,
                sortable: true,
                filter: true
            },
            columnDefs: columnDefs,

            rowData: [],
            rowSelection: {
                mode: 'multiRow',
                checkboxes: true
            },
            animateRows: true,
            pagination: true,
            paginationPageSize: 10,
            paginationPageSizeSelector: [10, 20, 50, 100],
            onCellEditingStopped: function(event) {
                updateProduct(event.data);
            }

        };

        const eGridDiv = document.querySelector('#myGrid');
        const gridApi = agGrid.createGrid(eGridDiv, gridOptions);

        function fetchProducts() {
            fetch('/api/products')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    gridApi.setGridOption('rowData', data);
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        }

        fetchProducts();

        document.getElementById('addProductButton').addEventListener('click', function() {
            const newProduct = {
                name: 'New Product',
                description: '',
                price: 0,
                quantity: 0
            };
            
            fetch('/api/products', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify(newProduct)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Product created successfully:', data);
                fetchProducts();
            })
            .catch(error => {
                console.error('Error creating product:', error);
                alert('Error creating product: ' + error.message);
            });
        });

        document.getElementById('deleteProductButton').addEventListener('click', function() {
            const selectedRows = gridApi.getSelectedRows();
            
            if (selectedRows.length === 0) {
                alert('Please select at least one product to delete.');
                return;
            }
            
            if (confirm(`Are you sure you want to delete ${selectedRows.length} product(s)?`)) {
                const deletePromises = selectedRows.map(row => {
                    return fetch(`/api/products/${row.id}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                        }
                    })
                    .then(response => {
                        const contentType = response.headers.get('content-type');
                        if (contentType && contentType.includes('application/json')) {
                            return response.json().then(data => {
                                if (data.error) {
                                    throw new Error(data.message);
                                }
                                return response;
                            });
                        }
                        
                        if (!response.ok) {
                            throw new Error(`Failed to delete product with ID ${row.id}: ${response.statusText}`);
                        }
                        return response;
                    });
                });
                
                Promise.all(deletePromises)
                    .then(() => {
                        console.log('All products deleted successfully');
                        fetchProducts();
                    })
                    .catch(error => {
                        console.error('Error deleting products:', error);
                        alert('Error deleting products: ' + error.message);
                        fetchProducts();
                    });
            }
        });

        function updateProduct(product) {
            if (!product.id) {
                return;
            }
            
            const updateData = {
                name: product.name,
                description: product.description,
                price: product.price,
                quantity: product.quantity
            };
            
            fetch(`/api/products/${product.id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify(updateData)
            })
            .then(response => {
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new Error(`Server returned non-JSON response: ${response.status} ${response.statusText}`);
                }
                
                if (!response.ok) {
                    throw new Error(`Failed to update product: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    throw new Error(data.message);
                }
                console.log('Product updated successfully:', data);
                fetchProducts();
            })
            .catch(error => {
                console.error('Error updating product:', error);
                alert('Error updating product: ' + error.message);
                fetchProducts();
            });
        }

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
    

    <div id="myGrid" class="ag-theme-alpine"></div>

    <script>
        // Define column headers

        const columnDefs = [
            { headerName: 'ID', field: 'id', sortable: true, filter: true, editable: false },
            { headerName: 'Name', field: 'name', sortable: true, filter: true, editable: true },
            { headerName: 'Description', field: 'description', sortable: true, filter: true, editable: true },
            { headerName: 'Price', field: 'price', sortable: true, filter: true, editable: true },
            { headerName: 'Quantity', field: 'quantity', sortable: true, filter: true, editable: true },
            { headerName: 'Created At', field: 'created_at', sortable: true, filter: true, editable: false },
            { headerName: 'Updated At', field: 'updated_at', sortable: true, filter: true, editable: false }
        ];

        // Initialize grid options

        const gridOptions = {
            defaultColDef: {
                editable: true,
                sortable: true,
                filter: true
            },
            columnDefs: columnDefs,

            rowData: [],
            rowSelection: {
                mode: 'multiRow',
                checkboxes: true
            },
            animateRows: true,
            pagination: true,
            paginationPageSize: 10,
            paginationPageSizeSelector: [10, 20, 50, 100],
            onCellEditingStopped: function(event) {
                // Handle cell editing - automatically save changes
                updateProduct(event.data);
            }

        };

        // Create grid
        const eGridDiv = document.querySelector('#myGrid');

        const gridApi = agGrid.createGrid(eGridDiv, gridOptions);

        // Fetch data from API
        function fetchProducts() {
            fetch('/api/products')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    gridApi.setGridOption('rowData', data);
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        }

        // Initial data fetch
        fetchProducts();

        // Add product
        document.getElementById('addProductButton').addEventListener('click', function() {
            // Create a new empty product
            const newProduct = {
                name: 'New Product',
                description: '',
                price: 0,
                quantity: 0
            };
            
            // Send request to create new product
            fetch('/api/products', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify(newProduct)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Product created successfully:', data);
                // Refresh the grid
                fetchProducts();
            })
            .catch(error => {
                console.error('Error creating product:', error);
                alert('Error creating product: ' + error.message);
            });
        });

        // Delete selected products
        document.getElementById('deleteProductButton').addEventListener('click', function() {
            const selectedRows = gridApi.getSelectedRows();
            
            if (selectedRows.length === 0) {
                alert('Please select at least one product to delete.');
                return;
            }
            
            if (confirm(`Are you sure you want to delete ${selectedRows.length} product(s)?`)) {
                // Create an array of promises for delete requests
                const deletePromises = selectedRows.map(row => {
                    return fetch(`/api/products/${row.id}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                        }
                    })
                    .then(response => {
                        // Check if response is JSON
                        const contentType = response.headers.get('content-type');
                        if (contentType && contentType.includes('application/json')) {
                            return response.json().then(data => {
                                if (data.error) {
                                    throw new Error(data.message);
                                }
                                return response;
                            });
                        }
                        
                        if (!response.ok) {
                            // If response is not ok, throw an error
                            throw new Error(`Failed to delete product with ID ${row.id}: ${response.statusText}`);
                        }
                        return response;
                    });
                });
                
                // Wait for all delete requests to complete
                Promise.all(deletePromises)
                    .then(() => {
                        console.log('All products deleted successfully');
                        // Refresh the grid after successful deletion
                        fetchProducts();
                    })
                    .catch(error => {
                        console.error('Error deleting products:', error);
                        alert('Error deleting products: ' + error.message);
                        // Refresh the grid to show current data
                        fetchProducts();
                    });
            }
        });

        // Update product
        function updateProduct(product) {
            // Only update if product has an ID (not a new product)
            if (!product.id) {
                return;
            }
            
            // Prepare the data to be sent to the API
            const updateData = {
                name: product.name,
                description: product.description,
                price: product.price,
                quantity: product.quantity
            };
            
            fetch(`/api/products/${product.id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify(updateData)
            })
            .then(response => {
                // Check if response is JSON
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new Error(`Server returned non-JSON response: ${response.status} ${response.statusText}`);
                }
                
                if (!response.ok) {
                    // If response is not ok, throw an error with status text
                    throw new Error(`Failed to update product: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    // If server returns an error, throw it
                    throw new Error(data.message);
                }
                console.log('Product updated successfully:', data);
                // Refresh the grid
                fetchProducts();
            })
            .catch(error => {
                console.error('Error updating product:', error);
                alert('Error updating product: ' + error.message);
                // Refresh the grid to revert changes
                fetchProducts();
            });
        }

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