<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=`, initial-scale=1.0">
    <title>FisFis - Send anonymous messages and feedbacks</title>
    <link rel="stylesheet" href="public/css/public.min.css">
    <link rel="shortcut icon" href="public/images/logo.png" type="image/png">
</head>

<body>

    <header class="bg-slate-700 text-white py-2 px-4 flex items-center justify-between mb-6">
        <div class="flex items-center">
            <img src="public/images/logo.png" alt="" class="w-8">
            <h3 class="font-medium text-xl tracking-wider">FisFis</h3>
        </div>
        <div class="relative px-1.5 cursor-pointer" onclick="app.check_login();">
            <svg xmlns="http://www.w3.org/2000/svg" class="fill-current w-6" viewBox="0 0 16 16">
                <path
                    d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555ZM0 4.697v7.104l5.803-3.558L0 4.697ZM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757Zm3.436-.586L16 11.801V4.697l-5.803 3.546Z" />
            </svg>
            <span
                class="absolute -right-2 -top-2 text-xs w-4 flex items-center justify-content text h-4 bg-white text-slate-600 px-1 rounded-full">3</span>
        </div>
    </header>


    <form class="px-3 w-full" onsubmit="window.app.submit(event)" id="form">
        <div class="flex items-center mb-2">
            <div class="font-medium text-gray-700 inline-flex items-center gap-1"><img
                    src="<?=$user->profile_picture_url;?>"
                    alt="Jafran Hasan" class="w-8 rounded-full"><span><?=$user->name;?></span> <span
                    class="font-normal text-slate-400 text-sm">asked: </span></div>
        </div>
        <div
            class="text-center text-lg font-medium tracking-wide text-slate-600 p-6 bg-slate-50 border border-slate-200 rounded-md mb-5">
            <?= $question->text; ?>
        </div>

        <h2 class="text-slate-400 text-sm mb-1">Your answer <span class="text-xs">(max 300 characters)</span></h2>
        <textarea name="message" placeholder="Write your message here"
            class="border border-gray-300 rounded-sm w-full p-3 outline-none ring ring-transparent focus:ring-cyan-600 transition duration-150"
            rows="5" maxlength="300" spellcheck="true"></textarea>
        <div class="text-center">
            <button type="submit"
                class="bg-slate-700 text-white px-4 py-2 rounded-md hover:opacity-90 transition duration-150">Send
                message</button>
        </div>
 
    </form>


    <footer class="mt-8 px-3">
        <div class="mb-8 text-center">
            <h2 class="text-lg my-3 text-slate-700">Would like to receive secret messages?</h2>
            <div class="text-center">
                <button onclick="app.check_login();" scope="public_profile,email,profile_pic,name,id"
                    class="bg-blue-500 px-4 py-2 font-semibold text-white inline-flex items-center space-x-2 rounded">
                    <svg class="w-5 h-5 fill-current" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path
                            d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                    </svg>
                    <span>Login FisFis</span>
                </button></div>
        </div>
        <h2 class="text-xl tracking-wide">Project FisFis</h2>
        <div class="text-gray-400">Anonymous messages and feedbacks online without disclosing your identity. Receiver
            will NEVER know that who sent the message. Such you can know what your friends think about you behind. This
            app is just for entertainment only. </div>

    </footer>

    <script>
        const _ = {
            question_id: <?= $question->id; ?>,
            csrf: '12hg23f',
            user: {
                name: '<?= $user->name; ?>'
            }
        }
    </script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/public/js/public.min.js"></script>
</body>

</html>