# Laravel Product Table Package

Laravel package for managing product data with CRUD operations, AG Grid integration, and CORS support.

## Installation

1. Install the package via composer:
   ```bash
   composer require your-vendor/product-package
   ```

2. Publish the migrations (optional, if you want to use the provided table structure):
   ```bash
   php artisan vendor:publish --tag=product-package-migrations
   ```
   
3. Run the migrations (only if you published the migrations in step 2):
   ```bash
   php artisan migrate
   ```

4. Publish the views (optional, if you want to customize the AG Grid table):
   ```bash
   php artisan vendor:publish --tag=product-package-views
   ```

5. Publish the config file (optional, if you want to configure CORS):
   ```bash
   php artisan vendor:publish --tag=product-package-config
   ```

## Usage

### AG Grid Table View

The package includes a flexible AG Grid component that can be used with any data structure.

To use the provided view:
1. Register the package's routes in your `routes/web.php`:
   ```php
   use ProductPackage\Http\Controllers\ProductTableController;
   
   Route::get('/products', [ProductTableController::class, 'index']);
   ```
   
2. Or, you can simply include the view in your existing blade file:
   ```blade
   @include('product-package::product-table')
   ```

3. The view includes a flexible AG Grid implementation that you can customize:
   - Modify the column definitions to match your data structure
   - Change the API endpoint to fetch your data
   - Adjust grid options as needed

### How the Flexible AG Grid Component Works

The AG Grid component in this package is designed to be highly flexible and adaptable to various data structures:

1. **Column Definitions**: 
   - The component uses a JavaScript array for column definitions.
   - You can easily modify this array to match the fields in your database table.
   - Each column can have properties like `headerName`, `field`, `sortable`, `filter`, etc.

2. **Data Fetching**:
   - The component fetches data from an API endpoint using the Fetch API.
   - You can change the URL to point to your own API endpoint.
   - The component expects the API to return JSON data.

3. **Grid Options**:
   - The component uses a grid options object to configure various AG Grid features.
   - You can enable or disable features like row selection, pagination, animations, etc.
   - All AG Grid features are available for customization.

4. **Customization**:
   - You can publish the views and modify the HTML, CSS, and JavaScript as needed.
   - The component can be used with any database table structure.
   - You can add custom cell renderers, editors, and other AG Grid features.

Example of customizing the component for a users table:
```javascript
// Define column headers for a users table
const columnDefs = [
    { headerName: 'ID', field: 'id', sortable: true, filter: true },
    { headerName: 'Name', field: 'name', sortable: true, filter: true },
    { headerName: 'Email', field: 'email', sortable: true, filter: true },
    { headerName: 'Created At', field: 'created_at', sortable: true, filter: true }
];

// Fetch data from your users API
fetch('/api/users')
    .then(response => response.json())
    .then(data => {
        gridOptions.api.setRowData(data);
    });
```

### API Endpoints

The package provides the following API endpoints for product management (optional):
- `GET /api/products` - Get all products
- `POST /api/products` - Create a new product
- `GET /api/products/{id}` - Get a specific product
- `PUT /api/products/{id}` - Update a specific product
- `DELETE /api/products/{id}` - Delete a specific product

Note: These endpoints are provided for convenience and only work if you use the provided migrations and models. You can choose to use your own API endpoints with the AG Grid component.

### CORS Configuration

The package includes a CORS middleware that can be configured to allow requests from specific origins.

To use the CORS middleware:
1. Add it to your API routes:
   ```php
   Route::middleware(['product-package.cors'])->group(function () {
       // Your API routes here
   });
   ```

2. Publish the config file and modify the allowed origins:
   ```bash
   php artisan vendor:publish --tag=product-package-config
   ```
   
3. Edit the `config/product-package.php` file to add your allowed origins:
   ```php
   'cors' => [
       'allowed_origins' => [
           'http://localhost:3000',
           'https://yourdomain.com',
       ],
   ],
   ```

## Customization

You can customize the AG Grid table by publishing the views and modifying them:
```bash
php artisan vendor:publish --tag=product-package-views
```

The views will be published to `resources/views/vendor/product-package`.

In the published view, you can:
1. Modify the column definitions to match your data structure
2. Change the API endpoint to fetch your data
3. Adjust grid options as needed
4. Add custom styling or additional features

## Contributing

Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.