<!DOCTYPE html>
{{--http://10.10.3.166:8080--}}


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Real-Time Chat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        {{--#chatBox {--}}
        {{--    background-image: url('{{ asset('images/tabiat.png') }}'); /* To'liq yo'l bilan */--}}
        {{--    background-size: cover;--}}
        {{--    background-position: center;--}}
        {{--    background-repeat: no-repeat;--}}
        {{--}--}}

         .chat-container {
            background-image: url('{{ asset('images/360_F_815432104_01j4HoAKfRaNvnUKMnZh3YytG9eh3g7y.jpg') }}'); /* To'liq yo'l bilan */
            background-size: cover; /* Divni butunlay qoplash uchun */
            background-position: center; /* Rasmni markazlashtirish */
            background-repeat: no-repeat; /* Rasmni takrorlamaslik */
        }

        /* Add your CSS styles here */
        body {
            background-color: #343a40;
            color: white;
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
        }

        .message .time {
            color: #888; /* Soat matnining rangini kulrang qilish */
            font-size: 12px; /* Soat matnining o'lchamini kichikroq qilish */
        }

        .container {

            display: flex;
            width: 100%;
            height: 100vh;
            max-width: 1920px;
        }

        .sidebar {
            background-image: url('{{ asset('images/360_F_815432104_01j4HoAKfRaNvnUKMnZh3YytG9eh3g7y.jpg') }}');
            background-size: cover; /* Sidebarni to'liq qoplaydi */
            background-position: center; /* Rasmning markazini joylash */


            width: 350px;
            background-color: #222a35;
            padding: 20px;
            color: white;
            border-radius: 10px;
            /*margin-right: 20px;*/
            display: flex;
            flex-direction: column;
            height: 100%;
            overflow-y: auto;
        }

        .sidebar h3 {
            color: #00aaff;
        }

        .contact {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding: 10px;
            /*border-radius: 8px;*/
            background-color: #2a2f3a;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .contact:hover {
            background-color: #4e5d6a;
        }

        .contact img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .contact .name {
            font-weight: bold;
        }

        .chat-container {
            display: flex;
            flex-direction: column;
            flex-grow: 1;
            height: 100%;
        }

        .chat-header {
            /*background-color: #2a2f3a;*/
            color: white;
            /*padding: 10px 20px;*/
            border-radius: 10px 10px 0 0;
        }

        .chat-box {
            flex-grow: 1;
            overflow-y: auto;
            padding: 20px;
            /*background-color: #2a2f3a;*/
            border-radius: 0 0 10px 10px;
            margin-bottom: 15px;
            height: 130%;
        }

        .message {
            display: flex;
            margin-bottom: 15px;
        }

        .message.user {
            justify-content: flex-end;
        }

        .message .content {
            /*max-width: 70%;*/
            padding: 12px;
            border-radius: 20px;
            background-color: #4e5d6a;
            word-wrap: break-word;
        }

        .message.user .content {
            background-color: #00aaff;
        }

        .input-box {
            padding: 12px;
            /*background-color: #222a35;*/
            border-radius: 10px;
        }

        .input-box input {
            flex-grow: 1;
            width: 90%;
            border: none;
            background-color: #2a2f3a;
            color: white;
            border-radius: 20px;
            padding: 10px 15px;
            margin-right: 10px;
            font-size: 16px;
        }

        .input-box button {
            border: none;
            background-color: #4e5d6a;
            color: white;
            border-radius: 50%;
            padding: 12px;
            cursor: pointer;
            font-size: 18px;
        }

        .input-box button:hover {
            background-color: #00aaff;
        }

    </style>
</head>
<body>
<div class="container" id="chat">

    <!-- Sidebar for contacts -->
    <div class="sidebar">
        <h3>Contacts</h3>


        {{--        @dump($users)--}}
        @foreach ($users as $user)
            @php
                $userId = json_encode($user->id);
            @endphp
            <button id="contact" class="contact" data-user-id="{{ $user->id }}">
                <img src="{{ asset('images/user.jpeg') }}" alt="User Image">
                <div>
                    <div class="name">{{ $user->name }}</div>
                </div>
            </button>

            {{--            <a href="/contact/user_id/{{$user->id }}"  id="UserId" class="contact">--}}
            {{--                <img src="{{ asset('images/user.jpeg') }}" alt="User Image">--}}
            {{--                <div>--}}
            {{--                    <div class="name">{{ $user->name }}</div>--}}
            {{--                </div>--}}
            {{--            </a>--}}

        @endforeach

        <!-- Add more contacts here -->
    </div>

    <!-- Chat container -->
    <div class="chat-container">
        <div class="chat-header" id="chatHeader">

        </div>
        <div class="chat-box" id="chatBox">
            <!-- Messages will appear here -->
            @if (isset($messages))
                @foreach($messages as $message)
                    @if ($message->sender_id == Auth::id())
                        <div class="message user">
                            <div class="message">
                                <div class="content">{{ $message->text }}
                                    <div class="time">{{ $message->created_at->format('h:i A') }}</div>

                                </div>
                            </div>
                        </div>
                    @elseif($message->sender_id == $receiverUSER->id)
                        <div class="message receiver">
                            <div class="message">
                                <div class="content">{{ $message->text }}
                                    <div class="time">{{ $message->created_at->format('h:i A') }}</div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif


        </div>
        <div class="input-box" id="inputBox">
{{--           forma keladi shu yerga--}}
            <input type="text" id="messageInput" name="text" placeholder="Type a message..." required>
            <button type="button" id="sendMessageBtn"><i class="fas fa-paper-plane"></i></button>
            <input type="hidden" id="receiver_id" value="${receiverUSER.id}">
            <input type="hidden" id="sender_id" value="${senderUSER.id}">


        </div>
    </div>
</div>
<script>
    document.querySelectorAll('.contact').forEach((button) => {
        button.addEventListener('click', function () {
            const userId = this.getAttribute('data-user-id');
            fetch(`http://localhost:8080/contact/user_id/${userId}`)
                .then(response => response.json())
                .then(data => {
                    sendUserId(data)
                })
        });
    });



    function sendUserId(data) {
        const chat = document.querySelector("#chatBox");
        const messages = data.messages;
        const receiverUSER = data.receiverUSER;
        const senderUSER = data.senderUSER;
        chat.innerHTML = "";

        messages.forEach(message => {
            // senderUSER.id ni ishlatamiz
            if (message.sender_id === senderUSER.id) {
                chat.innerHTML += `
                <div class="message user">
                    <div class="message">
                        <div class="content">${message.text}
                            <div class="time">${new Date(message.created_at).toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit'
                })}</div>
                        </div>
                    </div>
                </div>`;
            } else if (message.sender_id === receiverUSER.id) {
                chat.innerHTML += `
                <div class="message receiver">
                    <div class="message">
                        <div class="content">${message.text}
                            <div class="time">${new Date(message.created_at).toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit'
                })}</div>
                        </div>
                    </div>
                </div>`;
            }
        });
        const inputBoxt = (receiverUSER, senderUSER) => {
            const inputbox = document.querySelector("#inputBox");
            inputbox.innerHTML = `
            <input type="text" id="messageInput" name="text" placeholder="Type a message..." required>
            <button type="button" id="sendMessageBtn"><i class="fas fa-paper-plane"></i></button>
            <input type="hidden" id="receiver_id" value="${receiverUSER.id}">
            <input type="hidden" id="sender_id" value="${senderUSER.id}">

`;
        };

        inputBoxt(receiverUSER, senderUSER);

        const button = document.querySelector('#sendMessageBtn');

        button.addEventListener('click', () => {
            const messageInput = document.querySelector('#messageInput');
            const message = messageInput.value;
            const receiverUSER = document.querySelector('#receiver_id')
            const senderUSER = document.querySelector('#sender_id')

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch("http://localhost:8080/send-message", {
                method: "POST",
                headers:{
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken
                },
                body: JSON.stringify({
                    text: message,
                    receiver_id: receiverUSER.value,
                    sender_id: senderUSER.value
                })
            })
                .then(response => response.json())
                .then(data => {
                    sendUserId(data)
                })

        });

    }

</script>
</body>
</html>
