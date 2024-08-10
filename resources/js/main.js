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
                                    <i class="fa-solid fa-circle-info fa-xl p-2" style="cursor: pointer; color: aqua"></i>
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


        //todo Funzione per salvare
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

                const formatTime = (time) => new Date(`1970-01-01T${time}Z`).toLocaleTimeString('it-IT', { hour: '2-digit', minute: '2-digit' });

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
                <i class="fa-solid fa-circle-info fa-xl p-2" style="cursor: pointer; color: aqua"></i>
                <span onclick="deleteTrain(${train.id})" style="cursor: pointer;"><i class="fa-regular fa-trash-can fa-lg p-2"></i></span>
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



        function refreshSavedTrainsData(filterDate = null) {
            let url = '/trains/saved';
            if (filterDate) {
                url += `?date=${filterDate}`;
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
                    currentPage = 1; // Reset alla prima pagina quando si aggiorna la lista
                    renderTable();
                })
                .catch(error => console.error('Errore nel fetch:', error));
        }

        // Event listener per il bottone di filtro
        document.getElementById('filter-date-button').addEventListener('click', () => {
            const filterDate = document.getElementById('date-filter').value;
            if (filterDate) {
                refreshSavedTrainsData(filterDate);
            } else {
                refreshSavedTrainsData(); // Rimuove il filtro se la data non è selezionata
            }
        });

        // Inizializza i dati all'avvio senza filtro
        refreshSavedTrainsData();


        // function refreshSavedTrainsData() {
        //     fetch('/trains/saved', {
        //         method: 'GET',
        //         headers: {
        //             'X-Requested-With': 'XMLHttpRequest'
        //         }
        //     })
        //         .then(response => {
        //             if (!response.ok) {
        //                 throw new Error('Errore nella risposta dal server');
        //             }
        //             return response.json();
        //         })
        //         .then(data => {
        //             totalTrains = data.trains;
        //             renderTable();
        //         })
        //         .catch(error => console.error('Errore nel fetch:', error));
        // }

        // Event listener per il cambio di selezione delle righe per pagina
        document.getElementById('rows-per-page').addEventListener('change', () => {
            currentPage = 1; // Reset alla prima pagina quando si cambia il numero di righe
            renderTable();
        });
        refreshSavedTrainsData();


        //todo Funzione per cancellare
        window.deleteTrain = function (id) {
            if (typeof id === 'undefined') {
                console.error('ID del treno non definito.');
                return;
            }
            console.log('ID del treno da eliminare:', id);
            fetch(`/trains/${id}`, {
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
        refreshSavedTrainsData();
    }
});
