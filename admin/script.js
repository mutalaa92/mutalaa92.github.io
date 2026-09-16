
document.addEventListener("DOMContentLoaded", function () {

    /* =========================================
       MOBILE MENU
    ========================================= */

    const menuToggle = document.getElementById("menuToggle");
    const mainNav = document.getElementById("mainNav");

    if (menuToggle && mainNav) {

        menuToggle.addEventListener("click", function () {

            mainNav.classList.toggle("open");

        });


        const navLinks = mainNav.querySelectorAll("a");

        navLinks.forEach(function (link) {

            link.addEventListener("click", function () {

                mainNav.classList.remove("open");

            });

        });

    }


    /* =========================================
       SEARCH
    ========================================= */

    const searchInput = document.getElementById("siteSearch");
    const clearSearch = document.getElementById("clearSearch");

    const searchItems =
        document.querySelectorAll(".search-item");


    if (searchInput) {

        searchInput.addEventListener("input", function () {

            const query =
                searchInput.value
                    .trim()
                    .toLowerCase();

            if (clearSearch) {

                clearSearch.classList.toggle(
                    "visible",
                    query.length > 0
                );

            }


            searchItems.forEach(function (item) {

                const searchableText =
                    (
                        item.textContent +
                        " " +
                        (item.dataset.search || "")
                    ).toLowerCase();

                if (
                    query === "" ||
                    searchableText.includes(query)
                ) {

                    item.classList.remove(
                        "search-hidden"
                    );

                } else {

                    item.classList.add(
                        "search-hidden"
                    );

                }

            });

        });

    }


    /* =========================================
       CLEAR SEARCH
    ========================================= */

    if (clearSearch) {

        clearSearch.addEventListener("click", function () {

            if (!searchInput) return;

            searchInput.value = "";

            searchInput.dispatchEvent(
                new Event("input")
            );

            searchInput.focus();

        });

    }


    /* =========================================
       CURRENT YEAR
    ========================================= */

    const currentYear =
        document.getElementById("currentYear");

    if (currentYear) {

        currentYear.textContent =
            new Date().getFullYear();

    }

});
