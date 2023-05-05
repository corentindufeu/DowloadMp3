<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Mp3 Dowload</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css"  href="style.css">
    <meta charset="utf-8">
</head>
<body>
    <div id="contentBox" class="position-absolute">
        <form method="post" action="" class="w-100 h-100 d-flex flex-column justify-content-evenly align-items-center">
            <input name="videoUrl" type="text" placeholder="url youtube">
            <input name="submitInput" type="submit" value="Convertir">
            <?php
                if (isset($_POST['submitInput']) && isset($_POST['videoUrl'])) {

                    if ($_POST['videoUrl'] == "") {
                        ?> <script>alert("Veuillez rentrer une valeur! :)")</script> <?php
                        exit;
                    }

                    /*Return the url video key*/

                    $arrayQuery = str_split($_POST['videoUrl']);
                    $idVideo = "";
                    for ($i=0; $i<count($arrayQuery); $i++) {
                        if ($arrayQuery[$i] == "v" && $arrayQuery[$i+1] == "="){
                            for ($j=$i+2; $j<count($arrayQuery); $j++){
                                if ($arrayQuery[$j] == "&") {
                                    break;
                                } else if ($arrayQuery[$j] != "=" || $arrayQuery[$j+1] != "=") {
                                    $idVideo = $idVideo.$arrayQuery[$j];
                                }
                            }
                            break;
                        }
                    }
                    if ($idVideo == "") {
                        ?> <script>alert("Veuillez rentrer une url valide! :)")</script> <?php
                        exit;
                    }

                    /*Api communication, return link for dowload video with his key*/
                    
                    $curl = curl_init();

                    curl_setopt_array($curl, [
                        CURLOPT_URL => "https://youtube-mp36.p.rapidapi.com/dl?id=".$idVideo,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "GET",
                        CURLOPT_HTTPHEADER => [
                            "X-RapidAPI-Host: youtube-mp36.p.rapidapi.com",
                            "X-RapidAPI-Key: b8720768f1msh29e8c2d3c6dc72dp16a6e6jsn4d855b4ab5f2",
                            "content-type: application/octet-stream"
                        ],
                        CURLOPT_SSL_VERIFYPEER => FALSE
                    ]);

                    $response = curl_exec($curl);
                    $err = curl_error($curl);
                    curl_close($curl);

                    if ($err) {
                        ?> <script>alert("cURL Error #: " + <?=$err ?>)</script> <?php
                    } else {

                        /*Return to the dowload link from api*/

                        $arrayResponse = json_decode($response);
                        $url = $arrayResponse->link;
                        if ($url == "") {
                            ?> <script>alert("Vous ne pouvez plus télécharger de vidéos, revenez plus tard :)")</script> <?php
                        } else {
                            header("Location: $url");
                        }
                    }
                }
            ?>
        </form>
    </div>

</body>
</html>