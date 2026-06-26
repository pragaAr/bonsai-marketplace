<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <title>Invoice - {{ $invoiceNumber }}</title>
  <style>
    body {
      font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
      color: #2D3E2F;
      margin: 0;
      padding: 20px;
      line-height: 1.4;
      background-color: #ffffff;
    }

    .invoice-box {
      max-width: 800px;
      margin: auto;
      padding: 10px;
    }

    .header-table {
      width: 100%;
      margin-bottom: 40px;
      border-collapse: collapse;
    }

    .header-table td {
      vertical-align: top;
    }

    .brand-logo {
      font-size: 28px;
      font-weight: bold;
      color: #2D3E2F;
      letter-spacing: -1px;
    }

    .brand-subtitle {
      font-size: 11px;
      color: #B4A67F;
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-top: 2px;
    }

    .invoice-meta {
      text-align: right;
      font-size: 13px;
    }

    .invoice-meta h2 {
      margin: 0 0 5px 0;
      color: #2D3E2F;
      font-size: 20px;
      font-weight: 500;
    }

    .invoice-meta p {
      margin: 2px 0;
      color: #5a5a5a;
    }

    .billing-table {
      width: 100%;
      margin-bottom: 40px;
      border-collapse: collapse;
      font-size: 13px;
    }

    .billing-table td {
      width: 50%;
      vertical-align: top;
    }

    .billing-title {
      font-weight: bold;
      color: #2D3E2F;
      margin-bottom: 5px;
      text-transform: uppercase;
      font-size: 11px;
      letter-spacing: 0.5px;
    }

    .items-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 13px;
      margin-bottom: 40px;
    }

    .items-table th {
      background-color: #2D3E2F;
      color: #F5F5F0;
      text-align: left;
      padding: 10px;
      font-weight: 500;
      text-transform: uppercase;
      font-size: 11px;
      letter-spacing: 0.5px;
    }

    .items-table td {
      padding: 12px 10px;
      border-bottom: 1px solid #f0f0eb;
    }

    .items-table tr:last-child td {
      border-bottom: 2px solid #2D3E2F;
    }

    .text-right {
      text-align: right;
    }

    .totals-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 13px;
    }

    .totals-table td {
      padding: 6px 10px;
    }

    .totals-table .total-label {
      text-align: right;
      color: #5a5a5a;
    }

    .totals-table .total-amount {
      text-align: right;
      font-weight: bold;
      color: #2D3E2F;
      font-size: 16px;
    }

    .footer {
      margin-top: 80px;
      border-top: 1px solid #f0f0eb;
      padding-top: 20px;
      text-align: center;
      font-size: 11px;
      color: #8c8c8c;
    }

    .footer p {
      margin: 3px 0;
    }
  </style>
</head>

<body>
  <div class="invoice-box">

    <!-- Top Header -->
    <table class="header-table">
      <tr>
        <td>
          <div class="brand-logo">bonsaiku</div>
          <div class="brand-subtitle">Living art, cultivated
            with patience</div>
        </td>
        <td class="invoice-meta">
          <h2>INVOICE</h2>
          <p><strong>Number:</strong> {{ $invoiceNumber }}
          </p>
          <p><strong>Date:</strong> {{ $date }}</p>
        </td>
      </tr>
    </table>

    <!-- Billing Info -->
    <table class="billing-table">
      <tr>
        <td>
          <div class="billing-title">From</div>
          <p><strong>bonsaiku Store</strong></p>
          <p>Jalan Serene Indah No. 8</p>
          <p>Tangerang, Banten, Indonesia</p>
          <p>hello@bonsaiku.com</p>
        </td>
        <td>
          <div class="billing-title">Billed To</div>
          <p><strong>Valued Customer</strong></p>
          <p>Mindful Spaces Collector</p>
          <p>customer@bonsaiku.com</p>
        </td>
      </tr>
    </table>

    <!-- Items Table -->
    <table class="items-table">
      <thead>
        <tr>
          <th>Bonsai Specimen</th>
          <th class="text-right" style="width: 100px;">Price
          </th>
          <th class="text-right" style="width: 80px;">Qty
          </th>
          <th class="text-right" style="width: 120px;">
            Subtotal</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($cart as $item)
          <tr>
            <td>
              <strong>{{ $item['name'] }}</strong>
            </td>
            <td class="text-right">Rp
              {{ number_format($item['price'], 0, ',', '.') }}
            </td>
            <td class="text-right">{{ $item['qty'] }}</td>
            <td class="text-right">Rp
              {{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <!-- Totals Table -->
    <table class="totals-table">
      <tr>
        <td style="width: 60%;"></td>
        <td class="total-label">Subtotal:</td>
        <td class="text-right" style="width: 120px;">Rp
          {{ number_format($subtotal, 0, ',', '.') }}</td>
      </tr>
      <tr>
        <td></td>
        <td class="total-label">Shipping:</td>
        <td class="text-right"
          style="color: #2D3E2F; font-weight: 500;">FREE
        </td>
      </tr>
      <tr>
        <td></td>
        <td class="total-label"
          style="font-weight: 600; font-size: 15px; color: #2D3E2F; padding-top: 10px;">
          Total:</td>
        <td class="total-amount" style="padding-top: 10px;">
          Rp {{ number_format($subtotal, 0, ',', '.') }}
        </td>
      </tr>
    </table>

    <!-- Stempel / Note -->
    <div class="footer">
      <p>Thank you for cultivating mindfulness through our
        living art.</p>
      <p>&copy; {{ date('Y') }} bonsaiku. All rights
        reserved.</p>
    </div>

  </div>
</body>

</html>
