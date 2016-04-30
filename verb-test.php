<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html" charset="utf-8" />
        <title>Italian Verb Conjugation Test</title>
        <link rel="stylesheet" href="verb-test.css" type="text/css" />
        <script src="verb-test.js" type="text/javascript"></script>
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:300,700,400' rel='stylesheet' type='text/css'>
        <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    </head>

    <body>

    <h1>Italian Verbs Test</h1>
    <h2>This site came about as I was learning Italian using <a href="https://www.duolingo.com/">Duolingo</a>. As the verbs started coming thick 
        and fast I found that the site had no obvious way of testing verb conjugations.</h2>
    <h3>Currently only indicative present tense verbs can be tested but I will add more as my Italian progresses.</h3>
    <p>Updated <time datetime="2015-03-13">March 13th, 2015</time> to include Verbs: Present 2.</p>
    <p>If you notice any errors please <a href="mailto:poc275@gmail.com?subject=Italian%20Verbs%20Test">let me know.</a></p>
    <p>How to use:</p>
    <ol>
        <li>Choose a verb from the drop down menu</li>
        <li>Conjugate</li>
        <li>Hit Go to see your results</li>
    </ol>

    <?php
        header('Content-Type: text/html; charset=utf-8');

		if (isset($_GET["action"]))
        {
            $verbResult = false;
            $ioResult = false;
            $tuResult = false;
            $luiResult = false;
            $noiResult = false;
            $voiResult = false;
            $loroResult = false;

            if (strcmp($_GET["action"], "check") == 0)
            {
                // We are checking a verb test
                $mConnection = mysqli_connect("poc275couk.ipagemysql.com", "visitor", "readonly");
                mysqli_set_charset($mConnection, "utf8");

                if (!$mConnection)
                {
                    // connection refused
                    echo "Error connecting to the database";
                }
                else
                {
                    if (mysqli_select_db($mConnection, "verbs"))
                    {
                        $verbToTest = $_POST["english"];

                        $query = "SELECT *
                                    FROM verbtable
                                    WHERE english=?";

                        $stmt = mysqli_prepare($mConnection, $query);
                        mysqli_stmt_bind_param($stmt, 's', $verbToTest);

                        if (!mysqli_stmt_execute($stmt))
                        {
                            echo "Database query failed";
                        }
                        else
                        {
                            mysqli_stmt_bind_result($stmt, $italian_col, $io_col, $tu_col, $lui_col, $noi_col, $voi_col, $loro_col, $english_col);

                            while (mysqli_stmt_fetch($stmt))
                            {
                                $correctAnswers = array('verb' => $italian_col,
                                                        'io' => $io_col,
                                                        'tu' => $tu_col,
                                                        'lui' => $lui_col,
                                                        'noi' => $noi_col,
                                                        'voi' => $voi_col,
                                                        'loro' => $loro_col);

                                // Verb
                                if (strcasecmp($italian_col, $_POST["verb"]) == 0)
                                {
                                    $verbResult = true;
                                }

                                // io
                                if (strcasecmp($io_col, $_POST["io"]) == 0)
                                {
                                    $ioResult = true;
                                }

                                // tu
                                if (strcasecmp($tu_col, $_POST["tu"]) == 0)
                                {
                                    $tuResult = true;
                                }

                                // lui/lei
                                if (strcasecmp($lui_col, $_POST["lui"]) == 0)
                                {
                                    $luiResult = true;
                                }

                                // noi
                                if (strcasecmp($noi_col, $_POST["noi"]) == 0)
                                {
                                    $noiResult = true;
                                }

                                // voi
                                if (strcasecmp($voi_col, $_POST["voi"]) == 0)
                                {
                                    $voiResult = true;
                                }

                                // loro
                                if (strcasecmp($loro_col, $_POST["loro"]) == 0)
                                {
                                    $loroResult = true;
                                }
                            }
                        }

                        mysqli_stmt_close($stmt);
                    }
                    else
                    {
                        echo "Error selecting database table";
                    }

                    mysqli_close($mConnection);
                }
            }
        }
	?>


    <form action="verb-test.php?action=check" method="POST">

        <fieldset>
            <p><label for="english">To </label>
            <?php
            
                $mConnection = mysqli_connect("poc275couk.ipagemysql.com", "visitor", "readonly");
                mysqli_set_charset($mConnection, "utf8");

                if (!$mConnection)
                {
                    // connection refused
                    echo "Error connecting to the database";
                }
                else
                {
                    if (mysqli_select_db($mConnection, "verbs"))
                    {
                        $cur = mysqli_query($mConnection, "SELECT english FROM verbtable ORDER BY  `verbtable`.`english` ASC");

                        $selectedVerb = "";

                        // pre-select correct verb
                        if (isset($_GET["action"]))
                        {
                            $selectedVerb = $_POST["english"];
                        }

                        echo "<select name='english' onchange='verbChanged()'>";

                        while ($row = mysqli_fetch_assoc($cur))
                        {
                            $verbName = $row["english"];

                            echo "<option value='";
                            echo $verbName;
                            echo "'";

                            if (strcmp($selectedVerb, "") != 0)
                            {
                                if (strcmp($selectedVerb, $verbName) == 0)
                                {
                                    echo " selected";
                                }
                            }

                            echo ">";
                            echo $verbName;
                            echo "</option>";
                        }

                        echo "</select></p>";

                        mysqli_free_result($cur);
                    }
                    else
                    {
                        echo "Error selecting database table";
                    }

                    mysqli_close($mConnection);
                }
            ?>

            <p><label for="verb">Verb</label>
            <input type="text" name="verb" maxlength="20" onfocus="inputFocus(this)" autofocus="true"
            <?php
                if (isset($_GET["action"]))
                {
                    echo " value ='";
                    echo $_POST["verb"];
                    echo "'";
                }
            ?>
            ></input>
            <?php
                if (isset($_GET["action"]))
                {
                    if ($verbResult)
                    {
                        echo "<i class='fa fa-check'></i>";
                    }
                    else
                    {
                        echo "<i class='fa fa-times'></i>";
                        echo "<span> ";
                        echo $correctAnswers["verb"];
                        echo "</span>";
                    }
                }
            ?>
            </p>

            <p><label for="io">Io</label>
            <input type="text" name="io" maxlength="20" onfocus="inputFocus(this)"
            <?php
                if (isset($_GET["action"]))
                {
                    echo " value ='";
                    echo $_POST["io"];
                    echo "'";
                }
            ?>
            ></input>
            <?php
                if (isset($_GET["action"]))
                {
                    if ($ioResult)
                    {
                        echo "<i class='fa fa-check'></i>";
                    }
                    else
                    {
                        echo "<i class='fa fa-times'></i>";
                        echo "<span> ";
                        echo $correctAnswers["io"];
                        echo "</span>";
                    }
                }
            ?>
            </p>

            <p><label for="tu">Tu</label>
            <input type="text" name="tu" maxlength="20" onfocus="inputFocus(this)"
            <?php
                if (isset($_GET["action"]))
                {
                    echo " value ='";
                    echo $_POST["tu"];
                    echo "'";
                }
            ?>
            ></input>
            <?php
                if (isset($_GET["action"]))
                {
                    if ($tuResult)
                    {
                        echo "<i class='fa fa-check'></i>";
                    }
                    else
                    {
                        echo "<i class='fa fa-times'></i>";
                        echo "<span> ";
                        echo $correctAnswers["tu"];
                        echo "</span>";
                    }
                }
            ?>
            </p>

            <p><label for="lui">Lui/Lei</label>
            <input type="text" name="lui" maxlength="20" onfocus="inputFocus(this)"
            <?php
                if (isset($_GET["action"]))
                {
                    echo " value ='";
                    echo $_POST["lui"];
                    echo "'";
                }
            ?>
            ></input>
            <?php
                if (isset($_GET["action"]))
                {
                    if ($luiResult)
                    {
                        echo "<i class='fa fa-check'></i>";
                    }
                    else
                    {
                        echo "<i class='fa fa-times'></i>";
                        echo "<span> ";
                        echo $correctAnswers["lui"];
                        echo "</span>";
                    }
                }
            ?>
            </p>

            <p><label for="noi">Noi</label>
            <input type="text" name="noi" maxlength="20" onfocus="inputFocus(this)"
            <?php
                if (isset($_GET["action"]))
                {
                    echo " value ='";
                    echo $_POST["noi"];
                    echo "'";
                }
            ?>
            ></input>
            <?php
                if (isset($_GET["action"]))
                {
                    if ($noiResult)
                    {
                        echo "<i class='fa fa-check'></i>";
                    }
                    else
                    {
                        echo "<i class='fa fa-times'></i>";
                        echo "<span> ";
                        echo $correctAnswers["noi"];
                        echo "</span>";
                    }
                }
            ?>
            </p>

            <p><label for="voi">Voi</label>
            <input type="text" name="voi" maxlength="20" onfocus="inputFocus(this)"
            <?php
                if (isset($_GET["action"]))
                {
                    echo " value ='";
                    echo $_POST["voi"];
                    echo "'";
                }
            ?>
            ></input>
            <?php
                if (isset($_GET["action"]))
                {
                    if ($voiResult)
                    {
                        echo "<i class='fa fa-check'></i>";
                    }
                    else
                    {
                        echo "<i class='fa fa-times'></i>";
                        echo "<span> ";
                        echo $correctAnswers["voi"];
                        echo "</span>";
                    }
                }
            ?>
            </p>

            <p><label for="loro">Loro</label>
            <input type="text" name="loro" maxlength="20" onfocus="inputFocus(this)"
            <?php
                if (isset($_GET["action"]))
                {
                    echo " value ='";
                    echo $_POST["loro"];
                    echo "'";
                }
            ?>
            ></input>
            <?php
                if (isset($_GET["action"]))
                {
                    if ($loroResult)
                    {
                        echo "<i class='fa fa-check'></i>";
                    }
                    else
                    {
                        echo "<i class='fa fa-times'></i>";
                        echo "<span> ";
                        echo $correctAnswers["loro"];
                        echo "</span>";
                    }
                }
            ?>
            </p>

            <p id="button-wrapper"><button type="submit" value="Check">Go</button></p>

        </fieldset>
    </form>

    <div id="latin-chars">
        <div onclick="latinCharacterClick('à')">à</div>
        <div onclick="latinCharacterClick('è')">è</div>
        <div onclick="latinCharacterClick('ò')">ò</div>
    </div>

    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-44042917-4', 'auto');
      ga('send', 'pageview');

    </script>

    </body>
</html>