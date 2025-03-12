document.addEventListener("DOMContentLoaded", function () {
    fetch("stats/files") // Ce chemin est relatif à la page où tu te trouves, pas à une URL externe
        .then(response => response.json())
        .then(data => {
            const labels = data.map(item => item.user);
            const values = data.map(item => item.fileCount);

            const ctx = document.getElementById("fileChart").getContext("2d");
            new Chart(ctx, {
                type: "bar",
                data: {
                    labels: labels,
                    datasets: [{
                        label: "Nombre de fichiers publiés",
                        data: values,
                        backgroundColor: "rgba(75, 192, 192, 0.5)",
                        borderColor: "rgba(75, 192, 192, 1)",
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        })
        .catch(error => console.error("Erreur lors de la récupération des données :", error));
});