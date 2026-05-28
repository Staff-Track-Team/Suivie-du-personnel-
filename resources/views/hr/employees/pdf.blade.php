<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Fiche Employé - {{ $employee->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
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
            font-size: 24px;
            color: #2c3e50;
        }
        .header p {
            margin: 5px 0;
            font-size: 14px;
            color: #7f8c8d;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #2c3e50;
            border-bottom: 1px solid #bdc3c7;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        .info-item {
            margin-bottom: 8px;
        }
        .info-label {
            font-weight: bold;
            color: #7f8c8d;
            display: inline-block;
            width: 120px;
        }
        .info-value {
            color: #2c3e50;
        }
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
        }
        .status-active {
            background-color: #d4edda;
            color: #155724;
        }
        .status-inactive {
            background-color: #f8d7da;
            color: #721c24;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #bdc3c7;
            text-align: center;
            font-size: 11px;
            color: #7f8c8d;
        }
        .documents-list {
            list-style: none;
            padding: 0;
        }
        .documents-list li {
            padding: 5px 0;
            border-bottom: 1px solid #ecf0f1;
        }
        .contract-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #3498db;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>FICHE EMPLOYÉ</h1>
        <p>CFP-CMD - Direction des Ressources Humaines</p>
        <p>Généré le {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <!-- Informations Personnelles -->
    <div class="section">
        <div class="section-title">INFORMATIONS PERSONNELLES</div>
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Matricule:</span>
                <span class="info-value">{{ $employee->matricule }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Nom & Prénoms:</span>
                <span class="info-value">{{ $employee->name }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Email:</span>
                <span class="info-value">{{ $employee->email }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Rôle:</span>
                <span class="info-value">{{ ucfirst($employee->role) }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Statut:</span>
                <span class="info-value">
                    <span class="status-badge {{ $employee->status === 'Actif' ? 'status-active' : 'status-inactive' }}">
                        {{ $employee->status }}
                    </span>
                </span>
            </div>
            <div class="info-item">
                <span class="info-label">Date de naissance:</span>
                <span class="info-value">{{ $employee->birthday ? \Carbon\Carbon::parse($employee->birthday)->format('d/m/Y') : 'Non renseignée' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Sexe:</span>
                <span class="info-value">{{ $employee->gender ?? 'Non renseigné' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Téléphone:</span>
                <span class="info-value">{{ $employee->code_phone && $employee->phone ? $employee->code_phone . ' ' . $employee->phone : 'Non renseigné' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">N° CNI:</span>
                <span class="info-value">{{ $employee->numero_cni ?? 'Non renseigné' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">N° CNPS:</span>
                <span class="info-value">{{ $employee->numero_cnps ?? 'Non renseigné' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Situation mat.:</span>
                <span class="info-value">{{ $employee->situation_matrimoniale ?? 'Non renseignée' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Nombre d'enfants:</span>
                <span class="info-value">{{ $employee->nombre_enfants }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Niveau d'éducation:</span>
                <span class="info-value">{{ $employee->niveau_education ?? 'Non renseigné' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Compte bancaire:</span>
                <span class="info-value">{{ $employee->compte_bancaire ?? 'Non renseigné' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Contact d'urgence:</span>
                <span class="info-value">{{ $employee->nom_urgence && $employee->contact_urgence ? $employee->nom_urgence . ' - ' . $employee->contact_urgence : 'Non renseigné' }}</span>
            </div>
        </div>
    </div>

    <!-- Informations Professionnelles -->
    @if($employee->employeeInfo)
        <div class="section">
            <div class="section-title">INFORMATIONS PROFESSIONNELLES</div>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Département:</span>
                    <span class="info-value">{{ $employee->employeeInfo->department ?? 'Non renseigné' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Poste:</span>
                    <span class="info-value">{{ $employee->employeeInfo->position ?? 'Non renseigné' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Date d'embauche:</span>
                    <span class="info-value">{{ $employee->employeeInfo->hire_date ? \Carbon\Carbon::parse($employee->employeeInfo->hire_date)->format('d/m/Y') : 'Non renseignée' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Manager:</span>
                    <span class="info-value">{{ $employee->employeeInfo->manager_id ? \App\Models\User::find($employee->employeeInfo->manager_id)->name : 'Non renseigné' }}</span>
                </div>
            </div>
        </div>
    @endif

    <!-- Informations de Contrat -->
    @if($employee->contract)
        <div class="section">
            <div class="section-title">INFORMATIONS DE CONTRAT</div>
            <div class="contract-info">
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Type de contrat:</span>
                        <span class="info-value">{{ $employee->contract->type }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Date de début:</span>
                        <span class="info-value">{{ \Carbon\Carbon::parse($employee->contract->start_date)->format('d/m/Y') }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Date de fin:</span>
                        <span class="info-value">{{ $employee->contract->end_date ? \Carbon\Carbon::parse($employee->contract->end_date)->format('d/m/Y') : 'CDI' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Salaire:</span>
                        <span class="info-value">{{ number_format($employee->contract->salary, 0, ',', ' ') }} FCFA</span>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Documents -->
    @if($employee->documents->count() > 0)
        <div class="section">
            <div class="section-title">DOCUMENTS</div>
            <ul class="documents-list">
                @foreach($employee->documents as $document)
                    <li>
                        <strong>{{ $document->name }}</strong>
                        @if($document->description) - {{ $document->description }} @endif
                        <br><small>Ajouté le {{ \Carbon\Carbon::parse($document->created_at)->format('d/m/Y') }}</small>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="footer">
        <p>Ce document est généré automatiquement par le système de gestion des ressources humaines de CFP-CMD</p>
        <p>Pour toute question, contacter la Direction des Ressources Humaines</p>
    </div>
</body>
</html>
