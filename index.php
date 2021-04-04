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
            <input name="query" type="text" placeholder="url youtube">
            <input name="submitInput" type="submit" value="Convertir">
            <?php
                if (isset($_POST['submitInput']) && isset($_POST['query'])) {

                    /*Return the video key of url*/

                    $arrayQuery = str_split($_POST['query']);
                    $idVideo = "";
                    for ($i=0; $i<count($arrayQuery); $i++) {
                        if ($arrayQuery[$i] == "v" && $arrayQuery[$i+1] == "="){
                            for ($j=$i; $j<count($arrayQuery); $j++){
                                if ($arrayQuery[$j] == "&") {
                                    break;
                                } else if ($arrayQuery[$j] != "=" || $arrayQuery[$j+1] != "=") {
                                    $idVideo = $idVideo.$arrayQuery[$j];
                                }
                            }
                            break;
                        }
                    }

                    /*Api communication, return html with link for dowload video with his key*/
                    
                    $curl = curl_init();
                    curl_setopt_array($curl, [
                        CURLOPT_URL => "https://www.yt-download.org/public/api/button/mp3/".$idVideo,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "GET",
                        CURLOPT_SSL_VERIFYPEER => false
                    ]);
                    $response = curl_exec($curl);
                    if ($response === false) {
                        ?> <script>alert("Il semblerai qu'il y'ai un problème avec le site, nous allons essayer de le régler! :)")</script> <?php
                        exit;
                    }
                    curl_close($curl);
                    
                    /*Return dowload link from api*/

                    $arrayResponse = str_split($response);
                    $url = "";
                    for ($i=2050; $i<count($arrayResponse); $i++) {
                        if ($arrayResponse[$i] == "v" && $arrayResponse[$i+1] == "=") {
                            for ($j=$i+2; $j<count($arrayResponse); $j++) {
                                if ($arrayResponse[$j] == '"') {
                                    break;
                                } else {
                                    $url = $url.$arrayResponse[$j];
                                }
                            }
                            break;
                        }
                    }

                    if ($url = "") {
                        ?> <script>alert("Vous ne pouvez plus télécharger de vidéos, revenez plus tard :)")</script> <?php
                        exit;
                    } else {

                        ?>

                        <a href="<?= "https://www.yt-download.org/download/v=".$url ?>"  class="position-absolute"><span>Télécharger votre musique format mp3</span></a>
                        
                        <?php
                    }
                }
            ?>
        </form>
    </div>

</body>
</html>