//! Script per la schermata treni in transito
document.addEventListener('DOMContentLoaded', function () {
    const url = window.location.pathname;
    if (url === '/trains') {
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

        setInterval(refreshTrainsData, 60000);
        refreshTrainsData();

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
    }
});





//! Script per la schermata treni salvati
document.addEventListener('DOMContentLoaded', function () {
    const url = window.location.pathname;
    if (url === '/trains/saved') {
        function refreshSavedTrainsData() {
            fetch('/trains/saved', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Errore nella risposta dal server');
                    }
                    return response.json();
                })
                .then(data => {
                    const tableBody = document.getElementById('saved-trains-table-body');
                    if (!tableBody) {
                        console.error('Elemento con ID "saved-trains-table-body" non trovato.');
                        return;
                    }

                    tableBody.innerHTML = '';

                    data.trains.forEach(train => {
                        const delayAmount = train.delay_amount;
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
                        <td style="color: #a30000;">
                            <i class="fa-solid fa-train fa-xl"></i> Italo ${train.train_number}
                        </td>
                        <td>
                            ${train.departure_station_description} <strong>${train.departure_date}</strong>
                            <i class="fa-solid fa-arrow-right"></i>
                            ${train.arrival_station_description} <strong>${train.arrival_date}</strong>
                        </td>
                        <td>
                            Salvato il ${new Date(train.saved_at_date).toLocaleDateString()} alle ore ${new Date(train.saved_at_time).toLocaleTimeString()}
                        </td>
                        <td>
                            ${delayText}
                        </td>
                        <td>
                            <button>Dettagli</button>
                            <!-- Aggiungi altre funzionalità qui, come un pulsante di eliminazione se necessario -->
                        </td>
                    `;
                        tableBody.appendChild(row);
                    });
                })
                .catch(error => console.error('Errore nel fetch:', error));
        }

        setInterval(refreshSavedTrainsData, 60000);
        refreshSavedTrainsData();
    }
});
