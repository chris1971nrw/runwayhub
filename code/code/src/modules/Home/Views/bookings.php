<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>RunwayHub - Buchungen verwalten</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; }
        .header { background: #1a73e8; color: white; padding: 20px; text-align: center; }
        .container { max-width: 1200px; margin: 0 auto; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #f5f5f5; }
        .btn { padding: 8px 15px; background: #1a73e8; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .btn:hover { background: #1557b0; }
        .btn-danger { background: #da4453; }
    </style>
</head>
<body>
    <div class="header"><h1>🎫 Buchungen verwalten</h1></div>

    <div class="container">
        <div class="card">
            <h3>➕ Neue Buchung</h3>
            <form style="display: flex; gap: 10px; margin-top: 10px; flex-wrap: wrap;">
                <input type="text" placeholder="Flugnummer (z.B. LH456)" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                <input type="email" placeholder="Passagier-E-Mail" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                <input type="text" placeholder="Passagier-Name" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                <button type="submit" class="btn">Erstellen</button>
            </form>
        </div>

        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Referenz</th>
                        <th>Flugnummer</th>
                        <th>Passagier</th>
                        <th>E-Mail</th>
                        <th>Plätze</th>
                        <th>Status</th>
                        <th>Aktionen</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>REF123</td>
                        <td>LH456</td>
                        <td>Max Mustermann</td>
                        <td>max@beispiel.de</td>
                        <td>1A, 2B</td>
                        <td>Bestätigt</td>
                        <td><button class="btn">👁️</button> <button class="btn btn-danger">❌</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
