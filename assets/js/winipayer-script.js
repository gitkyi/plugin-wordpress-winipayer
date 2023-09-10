// script

document.addEventListener("DOMContentLoaded", function () {

    const env = document.getElementById("env");

    const date_start = document.getElementById("date_start");

    const date_end = document.getElementById("date_end");

    let linkCurrent = '';

    if (date_start.value > date_end.value) {
        date_start.value = '';
    }

    env.addEventListener('change', function () {

        const selectedEnvValue = env.value;

        linkCurrent = window.location.href;

        let baseURL = removeParametersFromURL(linkCurrent);

        let newUrl = baseURL + '?page=winipayer-transactions&env=' + selectedEnvValue

        window.location.href = newUrl;
    })

    date_start.addEventListener('change', function () {
        const dateStart = new Date(date_start.value);
        const dateEnd = new Date(date_end.value);

        if (dateStart > dateEnd) {
            dateEnd.setDate(dateStart.getDate() + 1);

            // Mettre à jour le champ date_end avec la nouvelle date
            const year = dateEnd.getFullYear();
            const month = String(dateEnd.getMonth() + 1).padStart(2, '0'); // Ajoute un zéro devant si nécessaire
            const day = String(dateEnd.getDate()).padStart(2, '0'); // Ajoute un zéro devant si nécessaire
            date_end.value = `${year}-${month}-${day}`;
        }

        if (date_start.value) {
            date_end.setAttribute('min', date_start.value);
        }
    });


    date_end.addEventListener('change', function () {
        const dateStart = new Date(date_start.value);
        const dateEnd = new Date(date_end.value);

        if (dateStart > dateEnd) {
            dateStart.setDate(dateEnd.getDate() - 1);

            // Mettre à jour le champ date_start avec la nouvelle date
            const year = dateStart.getFullYear();
            const month = String(dateStart.getMonth() + 1).padStart(2, '0'); // Ajoute un zéro devant si nécessaire
            const day = String(dateStart.getDate()).padStart(2, '0'); // Ajoute un zéro devant si nécessaire
            date_start.value = `${year}-${month}-${day}`;
        }

        if (!date_start.value) {
            date_start.setAttribute('max', date_end.value);
        }
    });
});

function removeParametersFromURL(url) {
    // Trouver l'index du premier "?" dans l'URL
    const indexOfQuestionMark = url.indexOf("?");

    // Si un "?" est trouvé, prendre la sous-chaîne avant le "?", sinon, garder l'URL intacte
    const baseURL = indexOfQuestionMark !== -1 ? url.substring(0, indexOfQuestionMark) : url;

    return baseURL;
}



// end  script