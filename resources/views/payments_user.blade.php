<!DOCTYPE html>
<html lang="arabic">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>User Payments Report - User #{{ $user->id }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .total-row {
            font-weight: bold;
            background-color: #e9e9e9;
        }
        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
<div class="header">
    <h1>User Payments Report</h1>
    <p>User: {{ $user->name }} (ID: {{ $user->id }})</p>
</div>

<table>
    <thead>
    <tr>
        <th>Payment ID</th>
        <th>Date</th>
        <th>Amount</th>
        <th>Current Spending</th>
        <th>Next Spending</th>
        <th>Counter ID</th>
    </tr>
    </thead>
    <tbody>
    @foreach($payments as $payment)
        <tr>
            <td>{{ $payment->id }}</td>
            <td>{{ $payment->date }}</td>
            <td>${{ number_format($payment->amount, 2) }}</td>
            <td>${{ number_format($payment->current_spending, 2) }}</td>
            <td>${{ number_format($payment->next_spending, 2) }}</td>
            <td>{{ $payment->counter_id }}</td>
        </tr>
    @endforeach
    <tr class="total-row">
        <td colspan="2">Total</td>
        <td>${{ number_format($totalAmount, 2) }}</td>
        <td colspan="3"></td>
    </tr>
    </tbody>
</table>

<div class="footer">
    <p>Generated on: {{ $generatedDate }}</p>
</div>
</body>
</html>
