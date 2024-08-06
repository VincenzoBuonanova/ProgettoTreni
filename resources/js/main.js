// document.addEventListener('DOMContentLoaded', function () {
//     fetchTrains();
//     setInterval(fetchTrains, 60000); // Rinfresca ogni minuto
// });

// function fetchTrains() {
//     fetch('/trains')
//         .then(response => {
//             if (!response.ok) {
//                 throw new Error('Errore di rete: ' + response.statusText);
//             }
//             return response.json();
//         })
//         .then(data => {
//             const tableBody = document.querySelector('#trains-table tbody');
//             tableBody.innerHTML = ''; // Pulisci la tabella esistente

//             data.trains.forEach(train => {
//                 const delay = train.Disruption > 10 ? `Ritardo di ${train.Disruption} minuti` : train.Disruption < 0 ? 'In anticipo' : 'In Orario';
//                 const departureStation = train.Stations[0].StationName;
//                 const departureTime = train.Stations[0].ArrivalTime;
//                 const arrivalStation = train.Stations[train.Stations.length - 1].StationName;
//                 const arrivalTime = train.Stations[train.Stations.length - 1].ArrivalTime;

//                 tableBody.innerHTML += `
//                     <tr>
//                         <td>${train.TrainNumber}</td>
//                         <td>${departureStation}</td>
//                         <td>${departureTime}</td>
//                         <td>${arrivalStation}</td>
//                         <td>${arrivalTime}</td>
//                         <td>${delay}</td>
//                         <td><button onclick="saveTrain('${train.TrainNumber}', '${departureStation}', '${departureTime}', '${arrivalStation}', '${arrivalTime}', ${train.Disruption})">Salva</button></td>
//                     </tr>
//                 `;
//             });
//         })
//         .catch(error => {
//             console.error('Errore:', error);
//         });
// }

// function saveTrain(trainNumber, departureStation, departureTime, arrivalStation, arrivalTime, delay) {
//     fetch('/trains/save', {
//         method: 'POST',
//         headers: {
//             'Content-Type': 'application/json',
//             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
//         },
//         body: JSON.stringify({
//             train_number: trainNumber,
//             departure_station: departureStation,
//             departure_time: departureTime,
//             arrival_station: arrivalStation,
//             arrival_time: arrivalTime,
//             delay: delay
//         })
//     })
//         .then(response => {
//             if (!response.ok) {
//                 throw new Error('Errore di rete: ' + response.statusText);
//             }
//             return response.json();
//         })
//         .then(data => alert('Treno salvato!'))
//         .catch(error => console.error('Errore:', error));
// }
