
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Fetch from REST API</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        select, input, table, form {
            padding: 10px;
            font-size: 16px;
            margin: 20px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        .red {
            background: red;
        }
        .orange {
            background: orange;
        }
    </style>
</head>
<body>
    <h1>Shopify Web App</h1>

    <select id="userDropdown" class="form-control" onchange="showProductVariants(this)">
        <option value="">Select Product</option>
    </select>

    <table id="data-table">
        <thead>
            <tr>
                <th>Variant ID</th>
                <th>Variant Title</th>
                <th>Quantity</th>
            </tr>
        </thead>
        <tbody>
            <tr id="second-row"><td colspan="3">Loading data...</td></tr>
        </tbody>
    </table>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

    <script>
        // Hide table on first time loading
        $(document).ready(function() {
            $('table').hide();
        });

        // Use select2
        $("#userDropdown").select2({
            dropdownAutoWidth : true
        });

        // Fetch data from the API
        fetch('fetch_products.php')
            .then(response => response.json()) // Parse JSON data
            .then(data => {
                // Get the dropdown element
                const dropdown = document.getElementById('userDropdown');

                // Loop through the products and add into each option
                data.products.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id;  // Set the value as the user ID
                    option.textContent = item.title;  // Set the text as the user's name
                    dropdown.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error fetching data:', error);
                // Optionally handle error
                document.getElementById('userDropdown').innerHTML = '<option value="">Failed to load users</option>';
            });
        
        // Function to show product variants
        async function showProductVariants(param) {
            $('table').show();
            const id = param.value;
            fetch('fetch_product.php?id=' + id)
                .then(response => response.json())  // Parse the JSON response
                .then(data => {
                    // Populate the table
                    const tableBody = document.getElementById('data-table').getElementsByTagName('tbody')[0];
                    tableBody.innerHTML = '';  // Clear loading row

                    if (data.errors) {
                        alert('Product not found!');
                    }

                    // Loop for each variants
                    data.product.variants.forEach(item => {
                        const row = tableBody.insertRow();
                        row.insertCell(0).textContent = item.id;  // ID
                        row.insertCell(1).textContent = item.title;  // Title
                        row.insertCell(2).textContent = item.inventory_quantity;  // Quantity
                        if (item.inventory_quantity < 3) { // Set background colour based on requirements
                            tableBody.classList.remove("orange");
                            tableBody.classList.add("red");
                        } else if (item.inventory_quantity >= 3 && item.inventory_quantity < 10) { 
                            tableBody.classList.remove("red");
                            tableBody.classList.add("orange");
                        } else {
                            tableBody.classList.remove("orange");
                            tableBody.classList.remove("red");
                        }
                    });
                });
        }        
    </script>
</body>
</html>

