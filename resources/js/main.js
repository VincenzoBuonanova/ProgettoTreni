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
                        const delayAmount = train.DelayAmount;
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
                                    <i class="fa-solid fa-circle-info fa-xl p-2" style="cursor: pointer; color: aqua" onclick='showTrainDetails(${JSON.stringify(train)})'></i>
                                    <i class="fa-regular fa-floppy-disk fa-xl p-2" onclick='saveTrain(${JSON.stringify(train)})' style="cursor: pointer; color: green"></i>
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

        //todo funzione per modale dettagli
        window.showTrainDetails = function (train) {
            const modalTitle = document.getElementById('trainModalLabel');
            const modalBody = document.getElementById('trainModalBody');

            modalTitle.textContent = `Dettagli treno Italo ${train.TrainNumber}`;

            let stationsHTML = '';
            train.Stations.forEach(station => {
                stationsHTML += `
                    <tr>
                        <td>${station.StationNumber +1}. ${station.LocationDescription}</td>
                        <td>${station.EstimatedArrivalTime}</td>
                        <td>${station.ActualArrivalTime}</td>
                        <td>${station.EstimatedDepartureTime}</td>
                        <td>${station.ActualDepartureTime}</td>
                    </tr>
                `;
            });

            modalBody.innerHTML = `
                <table class="table">
                    <thead>
                        <tr>
                            <th>Stazione</th>
                            <th>Arrivo Previsto</th>
                            <th>Arrivo Effettivo</th>
                            <th>Partenza Prevista</th>
                            <th>Partenza Effettiva</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${stationsHTML}
                    </tbody>
                </table>
            `;

            const modal = new bootstrap.Modal(document.getElementById('trainModal'));
            modal.show();
        };

        //todo Funzione per salvare un treno
        window.saveTrain = function (train) {
            const trainPayload = {
                TrainNumber: train.TrainNumber,
                DepartureStationDescription: train.DepartureStationDescription,
                DepartureDate: train.DepartureDate,
                ArrivalStationDescription: train.ArrivalStationDescription,
                ArrivalDate: train.ArrivalDate,
                DelayAmount: train.Distruption ? train.Distruption.DelayAmount : null
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
        };

        //todo funzione per salvare tutti i treni
        window.saveAllTrains = function () {
            const tableBody = document.getElementById('trains-table-body');
            const rows = tableBody.querySelectorAll('tr');
            const savePromises = [];
            let successCount = 0;

            rows.forEach(row => {
                // tentativo di approccio 3: constanti
                const departureStationDescription = row.querySelector('td:nth-child(2)').childNodes[0].textContent.trim();
                const arrivalStationDescription = row.querySelector('td:nth-child(2)').childNodes[4].textContent.trim();

                const train = {
                    TrainNumber: row.querySelector('td:nth-child(1)').textContent.trim().replace('Italo ', ''),
                    DepartureStationDescription: departureStationDescription,
                    DepartureDate: row.querySelector('td:nth-child(2)').querySelector('strong').textContent,
                    ArrivalStationDescription: arrivalStationDescription,
                    ArrivalDate: row.querySelector('td:nth-child(2)').querySelectorAll('strong')[1].textContent,
                    DelayAmount: row.querySelector('td:nth-child(3)').textContent.includes('Ritardo')
                        ? parseInt(row.querySelector('td:nth-child(3)').textContent.match(/\d+/)[0])
                        : null
                };

                savePromises.push(
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
                                return response.text().then(text => {
                                    throw new Error(`Errore nel salvataggio del treno: ${text}`);
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                successCount++;
                            }
                        })
                        .catch(error => {
                            console.error('Errore nel salvataggio:', error);
                        })
                );
            });
            Promise.all(savePromises).then(() => {
                alert(`${successCount} treni salvati con successo`);
            });
        };

        //todo evento del bottone "Salva tutti i treni"
        document.querySelector('.save-all').addEventListener('click', saveAllTrains);
    }
});


//! Script per la schermata treni salvati
document.addEventListener('DOMContentLoaded', function () {
    const url = window.location.pathname;
    if (url === '/trains/saved') {

        let currentPage = 1;
        let totalTrains = [];

        function renderTable() {
            const rowsPerPage = parseInt(document.getElementById('rows-per-page').value, 10);
            const tableBody = document.getElementById('saved-trains-table-body');
            tableBody.innerHTML = '';

            const start = (currentPage - 1) * rowsPerPage;
            const end = start + rowsPerPage;
            const trainsToShow = totalTrains.slice(start, end);

            trainsToShow.forEach(train => {
                const delayAmount = train.delay_amount;
                let delayText = '';

                if (delayAmount < 0) {
                    delayText = '<i class="fa-solid fa-circle" style="color: green;"></i> In anticipo';
                } else if (delayAmount < 10) {
                    delayText = '<i class="fa-solid fa-circle" style="color: green;"></i> In orario';
                } else if (delayAmount !== null) {
                    delayText = `<i class="fa-solid fa-circle" style="color: red;"></i> Ritardo ${delayAmount} minuti`;
                }

                const formatTime = (time) => new Date(`1970-01-01T${time}`).toLocaleTimeString('it-IT', { hour: '2-digit', minute: '2-digit' });

                const row = document.createElement('tr');
                row.innerHTML = `
                    <td style="color: #a30000;">
                        <i class="fa-solid fa-train fa-xl"></i> Italo ${train.train_number}
                    </td>
                    <td>
                        ${train.departure_station_description} <strong>${formatTime(train.departure_date)}</strong>
                        <i class="fa-solid fa-arrow-right"></i>
                        ${train.arrival_station_description} <strong>${formatTime(train.arrival_date)}</strong>
                    </td>
                    <td>
                        Salvato il ${new Date(train.saved_at_date).toLocaleDateString('it-IT')} alle ore ${formatTime(train.saved_at_time)}
                    </td>
                    <td>
                        ${delayText}
                    </td>
                    <td>
                        <span onclick="deleteTrain(${train.id})" style="cursor: pointer;">
                            <i class="fa-regular fa-trash-can fa-lg p-2"></i>
                        </span>
                    </td>
                `;
                tableBody.appendChild(row);
            });

            const totalPages = Math.ceil(totalTrains.length / rowsPerPage);
            document.getElementById('page-info').textContent = `Pagina ${currentPage} di ${totalPages}`;

            document.getElementById('prev-page').disabled = currentPage === 1;
            document.getElementById('next-page').disabled = currentPage === totalPages;
        }

        //todo pulsanti di navigazione
        document.getElementById('prev-page').addEventListener('click', () => {
            if (currentPage > 1) {
                currentPage--;
                renderTable();
            }
        });

        document.getElementById('next-page').addEventListener('click', () => {
            const rowsPerPage = parseInt(document.getElementById('rows-per-page').value, 10);
            if (currentPage < Math.ceil(totalTrains.length / rowsPerPage)) {
                currentPage++;
                renderTable();
            }
        });


        function refreshSavedTrainsData(filterDate = null, delayFilter = 'all') {
            let url = '/trains/saved';
            const params = [];
            if (filterDate) {
                params.push(`date=${filterDate}`);
            }
            if (delayFilter !== 'all') {
                params.push(`delay=${delayFilter}`);
            }
            if (params.length > 0) {
                url += '?' + params.join('&');
            }

            fetch(url, {
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
                totalTrains = data.trains;
                currentPage = 1;
                renderTable();
            })
            .catch(error => console.error('Errore nel fetch:', error));
        }

        //todo Event listener per il bottone di filtro
        document.getElementById('filter-date-button').addEventListener('click', () => {
            const filterDate = document.getElementById('date-filter').value;
            const delayFilter = document.getElementById('delay-filter').value;
            refreshSavedTrainsData(filterDate, delayFilter);
        });


        //todo Event listener per il cambio di selezione delle righe per pagina
        document.getElementById('rows-per-page').addEventListener('change', () => {
            currentPage = 1;
            renderTable();
        });
        refreshSavedTrainsData();


        //todo Event listener per il filtro di ritardo
        document.getElementById('delay-filter').addEventListener('change', () => {
            const filterDate = document.getElementById('date-filter').value;
            const delayFilter = document.getElementById('delay-filter').value;
            refreshSavedTrainsData(filterDate, delayFilter);
        });
        refreshSavedTrainsData();


        //todo Funzione per cancellare
        window.deleteTrain = function (id) {
            if (typeof id === 'undefined') {
                console.error('ID del treno non definito.');
                return;
            }
            console.log('ID del treno da eliminare:', id);
            fetch(`/trains/delete/${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        console.error('Errore nella risposta del server:', data);
                        throw new Error(data.message || 'Errore nella cancellazione');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert('Treno eliminato con successo');
                    refreshSavedTrainsData();
                } else {
                    alert('Errore nella cancellazione del treno');
                }
            })
            .catch(error => {
                console.error('Errore nella cancellazione:', error);
            });
        };

        setInterval(refreshSavedTrainsData, 60000);
    }
});