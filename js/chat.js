const chatMessages = document.getElementById('chatMessages');
const chatInput = document.getElementById('chatInput');
const sendBtn = document.getElementById('sendBtn');

function addMessage(text, sender) {
    const messageDiv = document.createElement('div');
    messageDiv.className = sender === 'user' ? 'message user-message' : 'message ai-message';

    if (sender === 'ai') {
        messageDiv.innerHTML = `
            <div class="avatar"><i class="fas fa-microchip"></i></div>
            <div class="message-text">${text}</div>
        `;
    } else {
        messageDiv.innerHTML = `<div class="message-text">${text}</div>`;
    }

    chatMessages.appendChild(messageDiv);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

async function sendMessage(text) {
    if (!text.trim()) return;

    addMessage(text, 'user');
    chatInput.value = '';

    const typingDiv = document.createElement('div');
    typingDiv.className = 'message ai-message';
    typingDiv.id = 'typingIndicator';
    typingDiv.innerHTML = `<div class="avatar"><i class="fas fa-microchip"></i></div><div class="message-text">Thinking...</div>`;
    chatMessages.appendChild(typingDiv);
    chatMessages.scrollTop = chatMessages.scrollHeight;

    try {
        const response = await fetch('php/chat_process.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ message: text })
        });

        const data = await response.json();
        document.getElementById('typingIndicator').remove();

        if (data.reply) {
            addMessage(data.reply, 'ai');
        } else {
            addMessage('Something went wrong. Please try again.', 'ai');
        }
    } catch (error) {
        document.getElementById('typingIndicator').remove();
        addMessage('Connection error. Please try again.', 'ai');
    }
}

sendBtn.addEventListener('click', () => sendMessage(chatInput.value));

chatInput.addEventListener('keydown', (e) => {
    if (e.key === 'Enter') {
        sendMessage(chatInput.value);
    }
});

document.querySelectorAll('.suggestion-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const text = btn.textContent.trim();
        sendMessage(text);
    });
});