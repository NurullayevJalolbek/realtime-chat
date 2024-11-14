<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Real-Time Chat Interface</title>
    <style>
        /* style.css */

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #1e1e1e;
            color: #fff;
        }

        .chat-container {
            display: flex;
            width: 100%;
            height: 100vh;
        }

        .contacts {
            width: 25%;
            background-color: #2a2a2a;
            padding: 20px;
            overflow-y: auto;
        }

        .contacts h2 {
            font-size: 1.5em;
            margin-bottom: 15px;
        }

        .contact {
            /*display: flex;*/
            align-items: center;
            padding: 10px 0;
            cursor: pointer;
        }

        .contact img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .contact-name {
            font-size: 1em;
            color: #ddd;
        }

        .chat-main {
            width: 75%;
            display: flex;
            flex-direction: column;
            background-color: #333;
        }

        .chat-header {
            padding: 15px;
            background-color: #444;
            text-align: center;
        }

        .chat-messages {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .message {
            padding: 10px;
            border-radius: 8px;
            max-width: 60%;
            position: relative;
        }

        .sent {
            background-color: #1f8ef1;
            align-self: flex-end;
            color: #fff;
        }

        .received {
            background-color: #3a3b3c;
            align-self: flex-start;
            color: #ddd;
        }

        .chat-input {
            display: flex;
            padding: 10px;
            background-color: #444;
        }

        .chat-input input {
            flex: 1;
            padding: 10px;
            font-size: 1em;
            border: none;
            border-radius: 5px;
            margin-right: 10px;
            background-color: #555;
            color: #ddd;
        }

        .chat-input button {
            padding: 10px 15px;
            background-color: #1f8ef1;
            border: none;
            color: #fff;
            font-size: 1em;
            border-radius: 5px;
            cursor: pointer;
        }

        .chat-input input:focus,
        .chat-input button:focus {
            outline: none;
        }
        /*Soat*/

         .timestamp {
             font-size: 0.9em;
             color: black; /* Qora rang */
             margin-left: 10px;
         }


        /*CHATGPT KOD MESSAGE UCHUN */
        .chat-messages {
            display: flex;
            flex-direction: column;
        }

        .message-container {
            display: flex;
            flex-direction: column;
            margin: 10px 0;
        }

        .message {
            padding: 12px 18px; /* Xabarni biroz uzunroq qilish */
            border-radius: 25px; /* Qirralarini aylantirish */
            max-width: 80%; /* Xabarning maksimal kengligi */
            word-wrap: break-word;
            font-size: 1em;
        }

        /* Receiver xabarlarini chap tomonda ko'rsatish (yashil rang) */
        .received {
            align-self: flex-start;
            background-color: #4caf50; /* Yashil */
            color: white;
        }

        /* Sender xabarlarini o'ng tomonda ko'rsatish (ko'k rang) */
        .sent {
            align-self: flex-end;
            background-color: #2196f3; /* Ko'k */
            color: white;
        }

        .timestamp {
            font-size: 0.8em;
            color: #888;
            margin-bottom: 2px;
        }

    </style>

    @vite(['resources/js/app.js', 'resources/sass/app.scss'])
</head>
<body>
<div class="chat">







</div>
</body>
</html>
