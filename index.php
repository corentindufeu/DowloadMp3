<!DOCTYPE>
<html>
<head lang="fr">
    <title>Mp3 Dowload</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css"  href="style.css">
    <meta charset="utf-8">
</head>
<body>
    <div id="contentBox" class="position-absolute">
        <form method="post" action="" class="w-100 h-100 d-flex flex-column justify-content-evenly align-items-center">
            <input name="query" type="text" placeholder="url youtube">
            <input name="submitInput" type="submit">

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
                                } else if ($arrayQuery[$j] != "v" && $arrayQuery[$j] != "=") {
                                    $idVideo = $idVideo.$arrayQuery[$j];
                                }
                            }
                            break;
                        }
                    }

                    $curl = curl_init();

                    /*Api communication, return a link for dowload the video with his id*/
                    curl_setopt_array($curl, [
                        CURLOPT_URL => "https://youtube-to-mp32.p.rapidapi.com/yt_to_mp3?video_id=".$idVideo,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "GET",
                        CURLOPT_HTTPHEADER => [
                            "x-rapidapi-host: youtube-to-mp32.p.rapidapi.com",
                            "x-rapidapi-key: b8720768f1msh29e8c2d3c6dc72dp16a6e6jsn4d855b4ab5f2"
                        ],
                        CURLOPT_SSL_VERIFYPEER => false
                    ]);

                    $response = curl_exec($curl);
                    $err = curl_error($curl);

                    if ($err) {
                        echo "cURL Error #:" . $err;
                    } else {
                        $link = json_decode($response, true);

                        /*regulates name size video*/
                        $arrayName = str_split($link['Title']);
                        $nameVideo = "";
                        for ($i = 0; $i<32; $i++) {
                            $nameVideo = $nameVideo.$arrayName[$i];
                        }
                        if (count($arrayName)>35) {
                            $nameVideo = $nameVideo."...";
                        }

                        ?>

                        <a href="<?= $link['Download_url'] ?>"  class="position-absolute"><span><?= $nameVideo ?></span></a>
                        
                        <?php
                    }

                    curl_close($curl);
                } 
            ?>
        </form>
    </div>

</body>
</html>