function refreshTrainsData() {
    fetch('/trains/refresh')
    .then(response => response.json())
    .then(data => {
        if (data.trains) {
            const tableBody = document.getElementById('trains-table-body');
            tableBody.innerHTML = '';
            data.trains.forEach(train => {
                const row = document.createElement('tr');
                row.innerHTML =
                `
                        <td style="color: #a30000;"><i class="fa-solid fa-train fa-xl"></i> Italo ${train.TrainNumber}</td>
                        <td>${train.DepartureStationDescription} <strong>${train.DepartureDate}</strong> <i class="fa-solid fa-arrow-right"></i> ${train.ArrivalStationDescription} <strong>${train.ArrivalDate}</strong></td>
                        <td>
                            ${train.Distruption.DelayAmount < 0 ? '<i class="fa-solid fa-circle" style="color: green;"></i> In anticipo' : (train.Distruption.DelayAmount < 10 ? '<i class="fa-solid fa-circle" style="color: green;"></i> In orario' : '<i class="fa-solid fa-circle" style="color: red;"></i> Ritardo ' + train.Distruption.DelayAmount + ' minuti')}
                        </td>
                        <td>
                        <button>Dettagli</button>
                        <button onclick='saveTrain(${JSON.stringify(train)})'>Salva</button>
                        </td>
                        `;
                tableBody.appendChild(row);
            });
            document.getElementById('last-update').textContent = 'Ultimo aggiornamento: ' + data.lastUpdate;
        }
    })
    .catch(error => console.error('Errore nel fetch:', error));
}

// manda un aggiornamento ogni minuto
setInterval(refreshTrainsData, 60000);
document.addEventListener('DOMContentLoaded', refreshTrainsData);


window.saveTrain = function(train) {
    fetch('/trains/save', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(train)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Errore col Network');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert('Treno salvato con successo');
        } else {
            alert('Errore nel salvataggio del treno');
        }
    })
    .catch(error => {
        console.error('Errore nel salvataggio:', error);
    });
}