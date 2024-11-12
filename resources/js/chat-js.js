function sendMessage() {
    const chatInput = document.getElementById('chatInput');
    const chatMessages = document.getElementById('chatMessages');

    if (chatInput.value.trim() !== '') {
        const messageDiv = document.createElement('div');
        messageDiv.classList.add('message', 'sent');
        messageDiv.textContent = chatInput.value;
        chatMessages.appendChild(messageDiv);

        chatInput.value = '';
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
}
