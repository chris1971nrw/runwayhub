<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>RunwayHub - Flüge verwalten</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; }
        .header { background: #1a73e8; color: white; padding: 20px; text-align: center; margin-bottom: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px; }
        h1 { margin-bottom: 15px; }
        .flights-table { width: 100%; border-collapse: collapse; }
        .flights-table th, .flights-table td { padding: 10px; text-align: left; border-bottom: 1px solid #eee; }
        .flights-table th { background: #f5f5f5; }
        .status { padding: 4px 8px; border-radius: 4px; font-size: 0.8em; }
        .status.scheduled { background: #e8f0fe; color: #1a73e8; }
        .status.active { background: #e6f4ea; color: #1e8e3e; }
        .status.boarding { background: #fce8e6; color: #c5221f; }
        .status.in-flight { background: #e8f0fe; color: #1a73e8; }
        .status.landed { background: #e6f4ea; color: #1e8e3e; }
        .actions { margin-top: 10px; }
        .btn { display: inline-block; padding: 8px 15px; background: #1a73e8; color: white; text-decoration: none; border-radius: 4px; margin-right: 5px; }
        .btn:hover { background: #1557b0; }
        .btn-secondary { background: #5f6368; }
        .btn-danger { background: #da4453; }
    </style>
</head>
<body>
    <div class="header">
        <h1>🛫 Flüge verwalten</h1>
    </div>

    <div class="container">
        <div class="card">
            <div style="display: flex; gap: 10px; margin-bottom: 15px;">
                <input type="text" id="flightNumber" placeholder="Flugnummer (z.B. LH456)" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; flex: 1;">
                <input type="date" id="departureDate" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                <button onclick="searchFlight()" class="btn">🔍 Suchen</button>
                <button onclick="createFlight()" class="btn btn-secondary">➕ Neuer Flug</button>
            </div>

            <table class="flights-table">
                <thead>
                    <tr>
                        <th>Flugnummer</th>
                        <th>Abfahrt</th>
                        <th>Ziel</th>
                        <th>Abflugzeit</th>
                        <th>Ankunft</th>
                        <th>Status</th>
                        <th>Flugzeug</th>
                        <th>Pilot</th>
                        <th>Aktionen</th>
                    </tr>
                </thead>
                <tbody id="flightsList">
                    <tr>
                        <td>LH456</td>
                        <td>FRA</td>
                        <td>JFK</td>
                        <td>14:30</td>
                        <td>17:45</td>
                        <td><span class="status scheduled">Geplant</span></td>
                        <td>D-AIMA</td>
                        <td>Hans Mueller</td>
                        <td>
                            <a href="#" class="btn">👁️</a>
                            <a href="#" class="btn btn-danger">🗑️</a>
                        </td>
                    </tr>
                    <tr>
                        <td>LH458</td>
                        <td>FRA</td>
                        <td>JFK</td>
                        <td>18:30</td>
                        <td>21:45</td>
                        <td><span class="status scheduled">Geplant</span></td>
                        <td>D-AIMA2</td>
                        <td>---</td>
                        <td>
                            <a href="#" class="btn">👁️</a>
                            <a href="#" class="btn btn-danger">🗑️</a>
                        </td>
                    </tr>
                    <tr>
                        <td>BA123</td>
                        <td>LHR</td>
                        <td>FRA</td>
                        <td>12:00</td>
                        <td>14:30</td>
                        <td><span class="status active">Am Start</span></td>
                        <td>D-AIME</td>
                        <td>---</td>
                        <td>
                            <a href="#" class="btn">👁️</a>
                            <a href="#" class="btn btn-danger">🗑️</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="card">
            <h3>📝 Flugdetails</h3>
            <div id="flightDetails" style="margin-top: 10px;">
                <p><strong>Flug:</strong> LH456</p>
                <p><strong>Route:</strong> FRA → JFK</p>
                <p><strong>Abflug:</strong> 14:30 Uhr</p>
                <p><strong>Flugzeug:</strong> Boeing 737-800 (D-AIMA)</p>
                <p><strong>Status:</strong> Geplant</p>
            </div>
        </div>
    </div>

    <script>
        function searchFlight() {
            const flightNumber = document.getElementById('flightNumber').value;
            alert('Flug ' + flightNumber + ' gesucht (API-Call)');
        }

        function createFlight() {
            alert('Neuen Flug erstellen (API-Call)');
        }
    </script>
</body>
</html>
