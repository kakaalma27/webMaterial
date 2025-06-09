<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

    body {
        font-family: 'Poppins', sans-serif;
    }

    .card-hover:hover {
        transform: translateY(-5px);
        transition: all 0.3s ease;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .animate-bounce-custom {
        animation: bounce 2s infinite;
    }

    @keyframes bounce {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-10px);
        }
    }

    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    </style>
</head>

<body class="bg-blue-50">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-md">
            <div class="container mx-auto px-4 py-4 flex justify-between items-center">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-shopping-cart text-blue-600 text-2xl"></i>
                    <h1 class="text-xl font-bold text-blue-700">SalesDash</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <button class="p-2 text-blue-600 hover:bg-blue-50 rounded-full">
                        <i class="fas fa-bell"></i>
                    </button>
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white">
                            <i class="fas fa-user"></i>
                        </div>
                        <span class="hidden md:inline text-sm font-medium">Karyawan</span>
                    </div>
                </div>
            </div>
        </header>
        <div>
            @yield('produk')
        </div>


        <script>
        // Product price
        const productPrice = 79.99;
        let discount = 0;

        // Update totals
        function updateTotals() {
            const quantity = parseInt(document.getElementById('quantity').value);
            const subtotal = productPrice * quantity;
            const total = subtotal - discount;

            document.getElementById('subtotal').textContent = `$${subtotal.toFixed(2)}`;
            document.getElementById('total').textContent = `$${total.toFixed(2)}`;
        }

        // Quantity controls
        function increaseQuantity() {
            const input = document.getElementById('quantity');
            let value = parseInt(input.value);
            if (value < 142) {
                input.value = value + 1;
                updateTotals();
            }
        }

        function decreaseQuantity() {
            const input = document.getElementById('quantity');
            let value = parseInt(input.value);
            if (value > 1) {
                input.value = value - 1;
                updateTotals();
            }
        }

        // Apply coupon
        function applyCoupon() {
            const coupon = document.getElementById('coupon').value.trim().toUpperCase();
            const discountElement = document.getElementById('discount');

            if (coupon === 'SALE20') {
                const quantity = parseInt(document.getElementById('quantity').value);
                discount = productPrice * quantity * 0.2;
                discountElement.textContent = `-$${discount.toFixed(2)}`;
                discountElement.classList.add('text-green-600');
                updateTotals();

                // Show success message
                alert('Coupon applied successfully! 20% discount added.');
            } else if (coupon) {
                discount = 0;
                discountElement.textContent = '$0.00';
                discountElement.classList.remove('text-green-600');
                updateTotals();

                // Show error message
                alert('Invalid coupon code. Please try again.');
            }
        }

        // Process checkout
        function processCheckout() {
            const quantity = document.getElementById('quantity').value;
            const payment = document.getElementById('payment').value;
            const total = document.getElementById('total').textContent;

            alert(
                `Order confirmed!\n\nQuantity: ${quantity}\nPayment: ${payment}\nTotal: ${total}\n\nThank you for your purchase!`
            );

            // Reset form
            document.getElementById('quantity').value = 1;
            document.getElementById('payment').value = 'Credit Card';
            document.getElementById('coupon').value = '';
            discount = 0;
            document.getElementById('discount').textContent = '$0.00';
            updateTotals();
        }

        // Filter sales (placeholder)
        function filterSales() {
            alert('Filter options would appear here in a fully implemented version.');
        }

        // Initialize
        document.getElementById('quantity').addEventListener('change', updateTotals);
        document.getElementById('quantity').addEventListener('input', function() {
            if (this.value > 142) this.value = 142;
            if (this.value < 1) this.value = 1;
            updateTotals();
        });
        </script>

</body>

</html>