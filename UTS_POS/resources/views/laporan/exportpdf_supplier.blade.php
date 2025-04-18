<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<style>
body {
    font-family: "Arial", sans-serif;
    margin: 0;
    padding: 20px;
    color: #333;
    background-color: #f9f9f9;
}

.header {
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    color: black;
    padding: 20px;
    border-radius: 5px 5px 0 0;
    margin-bottom: 20px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.company-info {
    text-align: center;
}

.company-name {
    font-size: 22px;
    font-weight: bold;
    margin-bottom: 5px;
    letter-spacing: 1px;
}

.company-tagline {
    font-size: 14px;
    margin-bottom: 10px;
    opacity: 0.9;
}

.company-details {
    font-size: 12px;
    line-height: 1.5;
}

.logo-container {
    text-align: center;
    margin-bottom: 15px;
}

.logo {
    max-height: 80px;
}

.report-title {
    text-align: center;
    color: #1e3c72;
    font-size: 18px;
    font-weight: bold;
    margin: 20px 0;
    padding-bottom: 10px;
    border-bottom: 2px solid #1e3c72;
}

.table-container {
    width: 100%;
    margin: 0 auto;
    overflow-x: auto;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
    box-shadow: 0 2px 3px rgba(0,0,0,0.1);
}

.data-table thead {
    background: linear-gradient(135deg, #2a5298 0%, #1e3c72 100%);
    color: black;
}

.data-table th {
    padding: 12px 8px;
    text-align: left;
    font-weight: 600;
    font-size: 13px;
}

.data-table td {
    padding: 10px 8px;
    border-bottom: 1px solid #e0e0e0;
    font-size: 12px;
}

.data-table tr:nth-child(even) {
    background-color: #f5f9ff;
}

.data-table tr:hover {
    background-color: #ebf2ff;
}

.text-center {
    text-align: center;
}

.text-right {
    text-align: right;
}

.footer {
    margin-top: 30px;
    padding-top: 10px;
    border-top: 1px solid #ddd;
    font-size: 10px;
    color: #666;
    text-align: center;
}

.page-number:after {
    content: counter(page);
}

/* Blue accent elements */
.blue-accent {
    color: #1e3c72;
}

.badge {
    display: inline-block;
    padding: 3px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: bold;
    background-color: #e3f2fd;
    color: #1976d2;
}
</style>
</head>
<body>
    <div class="header">
        <div class="logo-container">
            <img src="{{ asset('autologo-kop.png') }}" class="logo">
        </div>
        <div class="company-info">
            <div class="company-name">PT. AUTOMOBILKU ABADI</div>
            <div class="company-tagline">PREMIUM AUTOMOTIVE SOLUTIONS</div>
            <div class="company-details">
                Jl. Soekarno-Hatta No. 123, Malang 65141<br>
                Phone (1234) 567890 | Fax (1234) 567890<br>
                www.automobilku.co.id
            </div>
        </div>
    </div>

    <div class="report-title blue-accent">
        LAPORAN DATA SUPPLIER
    </div>

    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th class="text-center" width="5%">No</th>
                    <th width="20%">Kode Supplier</th>
                    <th width="20%">Nama Supplier</th>
                    <th width="55%">Alamat</th>
                </tr>
            </thead>
            <tbody>
                @foreach($supplier as $s)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td><span class="badge">{{ $s->kode_supplier }}</span></td>
                    <td><strong>{{ $s->nama_supplier }}</strong></td>
                    <td>{{ $s->alamat }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        Generated on {{ $timestamp }} | Page <span class="page-number"></span>
    </div>
</body>
</html>