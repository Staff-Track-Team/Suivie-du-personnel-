<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Liste des Employés - Camtel</title>
    <style>
        body {
            font-family: sans-serif;
            color: #333;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #0056b3; /* Camtel Blue approx */
            padding-bottom: 10px;
        }
        .header h1 {
            color: #0056b3;
            margin: 0;
            text-transform: uppercase;
            font-size: 20px;
        }
        .header p {
            margin: 5px 0 0;
            color: #666;
            font-size: 10px;
        }
        .meta-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 10px;
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 8px 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f4f6f9;
            color: #0056b3;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
            border-top: 1px solid #ddd;
        }
        tr:nth-child(even) {
            background-color: #fafafa;
        }
        .status-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
        }
        .status-actif {
            background-color: #d1fae5;
            color: #065f46;
        }
        .status-inactif {
            background-color: #f3f4f6;
            color: #374151;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            font-size: 9px;
            color: #999;
            text-align: center;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Camtel System Administration</h1>
        <p>Document officiel - Liste des Employés</p>
    </div>

    <div class="meta-info">
        <div>Généré le : {{ date('d/m/Y à H:i') }}</div>
        <div>Auteur : {{ auth()->user()->name }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Matricule</th>
                <th>Nom Complet</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($employees as $employee)
            <tr>
                <td style="font-family: monospace;">{{ $employee->matricule }}</td>
                <td>{{ $employee->name }}</td>
                <td>{{ $employee->email }}</td>
                <td>{{ $employee->code_phone }} {{ $employee->phone }}</td>
                <td>
                    <span class="status-badge {{ $employee->status === 'Actif' ? 'status-actif' : 'status-inactif' }}">
                        {{ $employee->status }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        CAMTEL - Cameroon Telecommunications | Confidentiel | Page <span class="page-number"></span>
    </div>
</body>
</html>
