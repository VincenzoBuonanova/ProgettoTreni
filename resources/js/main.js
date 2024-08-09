function refreshTrainsData() {
    fetch('/trains/refresh')
    .then(response => response.json())
    .then(data => {
        if (data.trains) {
            const tableBody = document.getElementById('trains-table-body');
            tableBody.innerHTML = '';
            data.trains.forEach(train => {
                const disruption = train.Distruption || {};
                const delayAmount = disruption.DelayAmount;
                let delayText = '';

                if (delayAmount < 0) {
                    delayText = '<i class="fa-solid fa-circle" style="color: green;"></i> In anticipo';
                } else if (delayAmount < 10) {
                    delayText = '<i class="fa-solid fa-circle" style="color: green;"></i> In orario';
                } else if (delayAmount !== null) {
                    delayText = `<i class="fa-solid fa-circle" style="color: red;"></i> Ritardo ${delayAmount} minuti`;
                }

                const row = document.createElement('tr');
                row.innerHTML = `
                    <td style="color: #a30000;"><i class="fa-solid fa-train fa-xl"></i> Italo ${train.TrainNumber}</td>
                    <td>${train.DepartureStationDescription} <strong>${train.DepartureDate}</strong> <i class="fa-solid fa-arrow-right"></i> ${train.ArrivalStationDescription} <strong>${train.ArrivalDate}</strong></td>
                    <td>${delayText}</td>
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


// Aggiorna ogni minuto
setInterval(refreshTrainsData, 60000);
document.addEventListener('DOMContentLoaded', refreshTrainsData);


window.saveTrain = function (train) {
    const trainPayload = {
        TrainNumber: train.TrainNumber,
        DepartureStationDescription: train.DepartureStationDescription,
        DepartureDate: train.DepartureDate,
        ArrivalStationDescription: train.ArrivalStationDescription,
        ArrivalDate: train.ArrivalDate,
        DelayAmount: train.Distruption.DelayAmount
    };

    fetch('/trains/save', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(trainPayload)
    })
    .then(response => {
        if (!response.ok) {
            return response.text().then(text => {
                throw new Error(`Errore nel salvataggio del treno: ${text}`);
            });
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
