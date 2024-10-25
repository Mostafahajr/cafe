$(document).ready(function() {
    // Function to update the total price
    function updateTotalPrice() {
        var totalPrice = 0;
        $('#orderTable tr').each(function() {
            var price = parseFloat($(this).find('.item-price').text().substring(1));
            var quantity = parseInt($(this).find('.item-quantity').val());
            totalPrice += price * quantity;
        });
        $('#totalPrice').text(totalPrice.toFixed(2));
    }

    // Handle "Add to My Order" button click
    $(document).on('click', '.add-to-order', function() {
        var productId = $(this).data('product-id');
        var productName = $(this).data('product-name');
        var productPrice = $(this).data('product-price');
        var productImage = $(this).data('product-image');

        // Create a new row for the product in the order table
        var orderRow = '<tr>';
        orderRow += '<td><img src="' + productImage + '" alt="' + productName + '" style="width: 50px; height: 50px;"></td>';
        orderRow += '<td>' + productName + '</td>';
        orderRow += '<td class="item-price">$' + parseFloat(productPrice).toFixed(2) + '</td>';
        orderRow += '<td><input type="number" class="form-control item-quantity" value="1" min="1"></td>';
        orderRow += '<td><input type="text" class="form-control item-note" placeholder="Add a note"></td>';
        orderRow += '<td><button class="btn btn-danger remove-item">Delete</button></td>';
        orderRow += '</tr>';

        // Append the new row to the order table
        $('#orderTable').append(orderRow);

        // Update the total price
        updateTotalPrice();
    });

    // Handle quantity change
    $(document).on('change', '.item-quantity', function() {
        updateTotalPrice();
    });

    // Remove item from the order
    $(document).on('click', '.remove-item', function() {
        $(this).closest('tr').remove();
        updateTotalPrice();
    });
});