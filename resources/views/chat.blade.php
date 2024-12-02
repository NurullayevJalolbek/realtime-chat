<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Full-Screen Real-Time Chat</title>
@vite(['resources/js/app.js', 'resources/sass/app.scss'])
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>
<body>


<div id="chat" class="chat-container">

</div>
{{--<script>--}}
{{--    const sendBtn = document.getElementById('sendBtn');--}}
{{--    const chatInput = document.getElementById('chatInput');--}}
{{--    const chatMessages = document.getElementById('chatMessages');--}}

{{--    sendBtn.addEventListener('click', () => {--}}
{{--        const message = chatInput.value.trim();--}}
{{--        if (message) {--}}
{{--            const newMessage = document.createElement('div');--}}
{{--            newMessage.classList.add('message', 'sender');--}}
{{--            newMessage.innerHTML = `<p>${message}</p><span class="time">${new Date().toLocaleTimeString()}</span>`;--}}
{{--            chatMessages.appendChild(newMessage);--}}
{{--            chatInput.value = '';--}}
{{--            chatMessages.scrollTop = chatMessages.scrollHeight;--}}
{{--        }--}}
{{--    });--}}
{{--</script>--}}


</body>
</html>
