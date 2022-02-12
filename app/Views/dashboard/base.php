<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=content-width, initial-scale=1">
    <title>FisFis - An anonymous messaging platform</title>
    <link rel="manifest" href="manifest.json" />
    <meta name="theme-color" content="white" />
    <link rel="apple-touch-icon" href="public/images/logo.png" />
    <link rel="shortcut icon" href="public/images/logo.png" type="image/png">
    <link rel="stylesheet" href="/public/css/app.min.css">
</head>

<body>
    <div id="app"></div>


<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '516075399529358',
      cookie     : true,
      xfbml      : true,
      version    : 'v13.0'
    });
      
    FB.AppEvents.logPageView();   
      
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "https://connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
</script>


    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/public/js/app.min.js"></script>
    <script>
        try {
            if ("serviceWorker" in navigator) {
            window.addEventListener("load", function () {
                navigator.serviceWorker
                    .register("serviceWorker.js")
                    .then(res => console.log("service worker registered"))
                    .catch(err => console.log("service worker not registered", err))
            })
        }
        } catch(e){

        }
    </script>
</body>

</html>