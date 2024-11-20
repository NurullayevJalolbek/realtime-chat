<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Shabloni</title>
@vite(['resources/js/app.js', 'resources/sass/app.scss'])
</head>
<body>


<div class="chat">

</div>


<script>
    function toggleSidebarAndChatInput() {
        const sidebar = document.querySelector('.sidebar');
        const userInfoSidebar = document.querySelector('.user-info-sidebar');
        const chatWindow = document.querySelector('.chat-window');
        const chatInput = document.querySelector('.chat-input');

        // Chap va o'ng panellarni yashirish
        sidebar.classList.toggle('hidden');
        userInfoSidebar.classList.toggle('hidden');

        // Chat oynasini kengaytirish
        if (sidebar.classList.contains('hidden') && userInfoSidebar.classList.contains('hidden')) {
            chatWindow.classList.add('full-width');
        } else {
            chatWindow.classList.remove('full-width');
        }

        // Chat inputni kengaytirish yoki siqish
        chatInput.classList.toggle('expanded');
    }


</script>
</body>
</html>
