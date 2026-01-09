<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tax Receipt - Donation #{{ $donation->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            color: #333;
        }
        .receipt-info {
            margin-bottom: 30px;
        }
        .receipt-info table {
            width: 100%;
            border-collapse: collapse;
        }
        .receipt-info td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        .receipt-info td:first-child {
            font-weight: bold;
            width: 200px;
        }
        .amount-box {
            background-color: #f8f9fa;
            border: 2px solid #333;
            padding: 20px;
            text-align: center;
            margin: 30px 0;
        }
        .amount-box h2 {
            margin: 0;
            font-size: 36px;
            color: #28a745;
        }
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #ccc;
            font-size: 12px;
            color: #666;
        }
        .print-button {
            text-align: center;
            margin: 20px 0;
        }
        .print-button button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 30px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
        }
        @media print {
            .print-button {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="print-button">
        <button onclick="window.print()">Print Receipt</button>
    </div>

    <div class="header">
        <h1>UNHUSHED</h1>
        <p>Tax-Deductible Donation Receipt</p>
    </div>

    <div class="receipt-info">
        <table>
            <tr>
                <td>Receipt Number:</td>
                <td>{{ str_pad($donation->id, 8, '0', STR_PAD_LEFT) }}</td>
            </tr>
            <tr>
                <td>Date of Donation:</td>
                <td>{{ $donation->created_at->format('F d, Y') }}</td>
            </tr>
            <tr>
                <td>Donor Name:</td>
                <td>{{ $donation->user->name }}</td>
            </tr>
            <tr>
                <td>Donor Email:</td>
                <td>{{ $donation->user->email }}</td>
            </tr>
            <tr>
                <td>Donation Type:</td>
                <td>{{ $donation->recurring ? 'Monthly Recurring' : 'One-Time' }}</td>
            </tr>
            <tr>
                <td>Payment Method:</td>
                <td>{{ ucfirst($donation->payment_type ?? 'Card') }}</td>
            </tr>
            @if($donation->subscription_id)
            <tr>
                <td>Subscription ID:</td>
                <td>{{ $donation->subscription_id }}</td>
            </tr>
            @endif
            @if($donation->payment_id)
            <tr>
                <td>Payment ID:</td>
                <td>{{ $donation->payment_id }}</td>
            </tr>
            @endif
        </table>
    </div>

    <div class="amount-box">
        <p style="margin: 0; font-size: 18px; color: #666;">Amount Donated</p>
        <h2>${{ number_format($donation->amount, 2) }}</h2>
    </div>

    @if($donation->message)
    <div style="background-color: #f8f9fa; padding: 15px; margin: 20px 0; border-left: 4px solid #007bff;">
        <strong>Donor Message:</strong>
        <p style="margin: 10px 0 0 0;">{{ $donation->message }}</p>
    </div>
    @endif

    <div class="footer">
        <p>
            <strong>Thank you for your generous donation to UNHUSHED!</strong>
        </p>
        <p>
            UNHUSHED is a registered 501(c)(3) nonprofit organization.
            This donation is tax-deductible to the extent allowed by law.
            No goods or services were provided in exchange for this contribution.
        </p>
        <p>
            Please retain this receipt for your tax records.
            If you have any questions, please contact us at donations@unhushed.org
        </p>
        <p>
            <strong>Tax ID:</strong> XX-XXXXXXX<br>
            <strong>Receipt Generated:</strong> {{ now()->format('F d, Y \a\t g:i A') }}
        </p>
    </div>
</body>
</html>
