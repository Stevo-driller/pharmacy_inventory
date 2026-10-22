<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    {{-- CSRF token for Laravel POST requests --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @yield('title', 'Pharmacy Inventory System')
    </title>


    <style>

        * {
            box-sizing: border-box;
        }


        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            color: #333;
        }


        /* =========================
           NAVBAR
        ========================== */

        .navbar {
            background: #1e3a8a;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
        }


        .navbar h1 {
            margin: 0;
            font-size: 22px;
            white-space: nowrap;
        }


        .navbar-menu {
            display: flex;
            align-items: center;
            gap: 18px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }


        .navbar a {
            color: white;
            text-decoration: none;
            font-size: 14px;
        }


        .navbar a:hover {
            text-decoration: underline;
        }


        /* =========================
           USER / LOGOUT
        ========================== */

        .user-section {
            display: flex;
            align-items: center;
            gap: 12px;
            padding-left: 15px;
            border-left: 1px solid rgba(255, 255, 255, 0.35);
        }


        .user-name {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }


        .user-name strong {
            font-size: 14px;
        }


        .user-role {
            font-size: 12px;
            color: #dbeafe;
            text-transform: capitalize;
        }


        .logout-form {
            margin: 0;
        }


        .logout-btn {
            border: 1px solid rgba(255, 255, 255, 0.6);
            background: transparent;
            color: white;
            padding: 8px 14px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }


        .logout-btn:hover {
            background: white;
            color: #1e3a8a;
        }


        /* =========================
           MAIN CONTAINER
        ========================== */

        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }


        /* =========================
           PAGE HEADER
        ========================== */

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }


        .header h2 {
            margin: 0;
        }


        /* =========================
           BUTTONS
        ========================== */

        .btn {
            display: inline-block;
            text-decoration: none;
            border: none;
            padding: 10px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }


        .btn-primary {
            background: #1e3a8a;
            color: white;
        }


        .btn-success {
            background: #16a34a;
            color: white;
        }


        .btn-warning {
            background: #f59e0b;
            color: white;
        }


        .btn-danger {
            background: #dc2626;
            color: white;
        }


        .btn-secondary {
            background: #6b7280;
            color: white;
        }


        .btn:hover {
            opacity: 0.9;
        }


        /* =========================
           ALERTS
        ========================== */

        .alert {
            padding: 12px 15px;
            margin-bottom: 20px;
            border-radius: 6px;
        }


        .alert-success {
            background: #dcfce7;
            color: #166534;
        }


        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
        }


        /* =========================
           TABLES
        ========================== */

        .table-container {
            background: white;
            border-radius: 8px;
            overflow-x: auto;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }


        table {
            width: 100%;
            border-collapse: collapse;
        }


        th,
        td {
            padding: 14px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }


        th {
            background: #f8fafc;
            font-weight: 600;
        }


        .actions {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }


        .empty {
            text-align: center;
            padding: 40px;
            color: #777;
        }


        /* =========================
           FORMS
        ========================== */

        .form-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }


        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }


        .form-group {
            display: flex;
            flex-direction: column;
        }


        .form-group.full {
            grid-column: 1 / -1;
        }


        label {
            margin-bottom: 7px;
            font-weight: 600;
        }


        input,
        select,
        textarea {
            padding: 11px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
        }


        textarea {
            min-height: 100px;
            resize: vertical;
        }


        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 8px;
        }


        .checkbox-group input {
            width: auto;
        }


        .error {
            color: #dc2626;
            font-size: 13px;
            margin-top: 5px;
        }


        .form-actions {
            margin-top: 25px;
            display: flex;
            gap: 10px;
        }


        /* =====================================================
           PHARMACY POS
        ====================================================== */

        .pos-container {
            display: grid;
            grid-template-columns: 1.4fr 1fr;
            gap: 25px;
            align-items: start;
        }


        .pos-medicines,
        .pos-cart {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }


        .pos-medicines h3,
        .pos-cart h3 {
            margin-top: 0;
            margin-bottom: 20px;
        }


        /* =========================
           MEDICINE SEARCH
        ========================== */

        .search-input {
            width: 100%;
            margin-bottom: 20px;
            padding: 13px;
            border: 1px solid #d1d5db;
            border-radius: 7px;
            font-size: 15px;
        }


        .search-input:focus,
        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #1e3a8a;
            box-shadow: 0 0 0 2px rgba(30, 58, 138, 0.1);
        }


        /* =========================
           MEDICINE CARDS
        ========================== */

        .medicine-card {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            padding: 17px;
            margin-bottom: 12px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            background: #fafafa;
        }


        .medicine-card:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
        }


        .medicine-card h4 {
            margin: 0 0 8px 0;
            color: #1e3a8a;
        }


        .medicine-card p {
            margin: 4px 0;
            font-size: 14px;
        }


        .medicine-card .btn {
            white-space: nowrap;
        }


        /* =========================
           CUSTOMER SECTION
        ========================== */

        .customer-section {
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e5e7eb;
        }


        .customer-section h4,
        .payment-section h4 {
            margin-top: 0;
            margin-bottom: 12px;
        }


        .customer-section input {
            width: 100%;
            margin-bottom: 10px;
        }


        /* =========================
           CART
        ========================== */

        #cartItems {
            margin-bottom: 20px;
        }


        .empty-cart {
            text-align: center;
            padding: 30px 10px;
            color: #777;
            background: #f8fafc;
            border-radius: 7px;
        }


        .cart-item {
            display: grid;
            grid-template-columns: 1fr auto auto auto;
            gap: 12px;
            align-items: center;
            padding: 14px 0;
            border-bottom: 1px solid #eee;
        }


        .cart-item strong {
            font-size: 14px;
        }


        .cart-item small {
            color: #777;
        }


        /* =========================
           QUANTITY CONTROLS
        ========================== */

        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 8px;
        }


        .quantity-controls button {
            width: 28px;
            height: 28px;
            border: none;
            border-radius: 5px;
            background: #1e3a8a;
            color: white;
            cursor: pointer;
            font-size: 16px;
        }


        .quantity-controls button:hover {
            opacity: 0.85;
        }


        .quantity-controls span {
            min-width: 20px;
            text-align: center;
            font-weight: bold;
        }


        /* =========================
           REMOVE CART ITEM
        ========================== */

        .remove-item {
            border: none;
            background: #fee2e2;
            color: #dc2626;
            width: 28px;
            height: 28px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
        }


        .remove-item:hover {
            background: #fecaca;
        }


        /* =========================
           CART SUMMARY
        ========================== */

        .cart-summary {
            border-top: 1px solid #e5e7eb;
            padding-top: 15px;
            margin-top: 15px;
        }


        .cart-summary > div {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }


        .cart-summary input {
            width: 130px;
        }


        .total-row {
            font-size: 18px;
            padding-top: 12px;
            border-top: 2px solid #e5e7eb;
        }


        .total-row strong {
            color: #1e3a8a;
            font-size: 20px;
        }


        /* =========================
           PAYMENT
        ========================== */

        .payment-section {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }


        .payment-section label {
            display: block;
            margin-top: 12px;
            margin-bottom: 6px;
        }


        .payment-section select,
        .payment-section input {
            width: 100%;
        }


        .change-display {
            margin-top: 15px;
            padding: 15px;
            background: #f0fdf4;
            border-radius: 7px;
            display: flex;
            justify-content: space-between;
        }


        .change-display strong {
            color: #16a34a;
        }


        /* =========================
           COMPLETE SALE BUTTON
        ========================== */

        .complete-sale-btn {
            width: 100%;
            margin-top: 20px;
            padding: 14px;
            font-size: 16px;
            font-weight: bold;
        }


        /* =========================
           RESPONSIVE
        ========================== */

        @media (max-width: 900px) {

            .pos-container {
                grid-template-columns: 1fr;
            }

        }


        @media (max-width: 700px) {

            .form-grid {
                grid-template-columns: 1fr;
            }


            .form-group.full {
                grid-column: auto;
            }


            .navbar {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }


            .navbar-menu {
                justify-content: center;
            }


            .user-section {
                border-left: none;
                padding-left: 0;
                margin-top: 5px;
            }


            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }


            .cart-item {
                grid-template-columns: 1fr;
                gap: 8px;
            }

        }

    </style>

</head>


<body>

<nav class="navbar">

    <h1>Pharmacy Inventory System</h1>

    <div class="navbar-menu">

        {{-- Dashboard --}}
        @auth

            @if(Auth::user()->role === 'doctor')

                <a href="{{ route('doctor.dashboard') }}">
                    Dashboard
                </a>

            @else

                <a href="{{ route('dashboard') }}">
                    Dashboard
                </a>

            @endif

        @endauth


        {{-- Medicines --}}
        @auth
            @if(in_array(Auth::user()->role, ['admin', 'pharmacist']))
                <a href="{{ route('medicines.index') }}">Medicines</a>
            @endif
        @endauth


        {{-- Suppliers --}}
        @auth
            @if(in_array(Auth::user()->role, ['admin', 'pharmacist']))
                <a href="{{ route('suppliers.index') }}">Suppliers</a>
            @endif
        @endauth


        {{-- Stock --}}
        @auth
            @if(in_array(Auth::user()->role, ['admin', 'pharmacist']))
                <a href="{{ route('stock.index') }}">Stock</a>
            @endif
        @endauth


        {{-- POS --}}
        @auth
            @if(in_array(Auth::user()->role, ['admin', 'pharmacist', 'cashier']))
                <a href="{{ route('sales.index') }}">POS</a>
            @endif
        @endauth


        {{-- Sales History --}}
        @auth
            @if(in_array(Auth::user()->role, ['admin', 'pharmacist', 'cashier']))
                <a href="{{ route('sales.history') }}">Sales</a>
            @endif
        @endauth


        {{-- User Management --}}
        @auth
            @if(Auth::user()->role === 'admin')
                <a href="{{ route('users.index') }}">Users</a>
            @endif
        @endauth


        {{-- USER INFORMATION + LOGOUT --}}
        @auth

            <div class="user-section">

                <div class="user-name">

                    <strong>{{ Auth::user()->name }}</strong>

                    <span class="user-role">
                        {{ ucfirst(Auth::user()->role) }}
                    </span>

                </div>


                <form method="POST"
                      action="{{ route('logout') }}"
                      class="logout-form">

                    @csrf

                    <button type="submit"
                            class="logout-btn"
                            onclick="return confirm('Are you sure you want to logout?')">

                        Logout

                    </button>

                </form>

            </div>

        @endauth

    </div>

</nav>

<main class="container">


    {{-- SUCCESS MESSAGE --}}

    @if(session('success'))

        <div class="alert alert-success">

            {{ session('success') }}

        </div>

    @endif


    {{-- ERROR MESSAGE --}}

    @if(session('error'))

        <div class="alert alert-danger">

            {{ session('error') }}

        </div>

    @endif


    {{-- VALIDATION ERRORS --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please fix the following:
            </strong>


            <ul>

                @foreach($errors->all() as $error)

                    <li>

                        {{ $error }}

                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- PAGE CONTENT --}}

    @yield('content')


</main>


</body>

</html>