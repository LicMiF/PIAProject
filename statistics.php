<!DOCTYPE html>
<html lang="en">
<?php
    require_once "./core/utilities.php";
    include_once "./includes/head.php";
?>
<body>
    <?php
        allowAdminOnly();
        include_once "./includes/topnav.php";
        include_once "./includes/header.html";
        $classCount=countClasses();
        $classCountPerClassName=countClassesPerClassName();

    ?>

    <div class="row">
        <div class="stat-wrapper">

                <div class="stat-column">
                    <h2>Najpopularniji kursevi</h2>
                    <h4>Top 10 najpopularnijih kurseva po broju časova</h4>
                    <?php
                        arsort($classCount);
                        displayStats($classCount);
                    ?>
                </div>

                <div class="stat-column">
                    <h2>Najmanje popularni kursevi</h2>
                    <h4>Top 10 kurseva sa najmanjim brojem časova</h4>
                    <?php
                        asort($classCount);
                        displayStats($classCount);
                    ?>
                </div>

                <div class="stat-column">
                    <h2>Prosečan broj časova</h2>
                    <h4>Prosečan broj časova za svaki kurs, dobjen uprosečavanjem broja časova koji je svaki mentor održao za dati kurs</h4>
                    <?php
                        displayAverageStats($classCountPerClassName);
                    ?>
                </div>

        </div>
    </div>

    <div class="row">
        <div class="stat-wrapper">

                <div class="stat-column">
                    <h2>Najbolje ocenjeni mentori</h2>
                    <h4>Top 10 mentora sa najboljom prosečnom ocenom i broj glasova koji im je dodeljen</h4>
                    <?php
                        displayStatsRatings();
                    ?>
                </div>

                <div class="stat-column">
                    <h2>Najviše ocenjivani mentori</h2>
                    <h4>Top 10 mentora sa najvećim brojem dodeljenih ocena</h4>
                    <?php
                        displayStatsRatingCounts();
                    ?>
                </div>

                <div class="stat-column">
                    <h2>Najviše komentarisani mentori</h2>
                    <h4>Top 10 mentora sa najvećim brojem komentara na profilu</h4>
                    <?php
                        displayStatsCommentCounts();
                    ?>
                </div>

        </div>
    </div>

    <div class="row">
        <div class="stat-wrapper">
            <div class="full-stat">
                    <h2>Top 10</h2>
                    <h4>Top 10 mentora sa najvećim brojem održanih časova</h4>
                    <?php
                        displayStatsClassesCounts();
                    ?>
            </div>
        </div>
    </div>
    <!-- Animation related -->
    <script>
        $(document).ready(function () {
        $(".class-stat-container").each(function (index) {
            var $element = $(this);

            setTimeout(function () {
                $element.css({
                    opacity: 1,
                    transform: "translateX(0)"
                });
            }, index * 150);
        });
    });
    </script>

    <?php
        include_once "./includes/footer.html";
    ?>
</body>
</html>