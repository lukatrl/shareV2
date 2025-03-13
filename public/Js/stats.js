document.addEventListener("DOMContentLoaded", function () {
    fetch("stats/files")
        .then(response => response.json())
        .then(data => {
            const labels = data.map(item => item.user);
            const values = data.map(item => item.fileCount);

            const ctx = document.getElementById("fileChart").getContext("2d");
            window.fileChart = new Chart(ctx, {
                type: "bar",
                data: {
                    labels: labels,
                    datasets: [{
                        label: "Nombre de fichiers publiés",
                        data: values,
                        backgroundColor: "rgba(46, 164, 79, 0.5)", // Vert clair avec opacité (basé sur --custom-green)
                        borderColor: "rgb(46, 164, 79)", // Vert plein
                        borderWidth: 2
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