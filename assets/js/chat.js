let username = "";

function openChat() {
    document.getElementById('chatbot-box').style.display = 'block'; 
    document.getElementById('open-chat').style.display = 'none'; 
  
}

function closeChat() {
    document.getElementById('chatbot-box').style.display = 'none';
    document.getElementById('open-chat').style.display = 'block'; 
}

function handleEnter(event) {
    if (event.key === 'Enter') {
        sendMessage();
    }
}

function sendMessage() {
    const userMessage = document.getElementById('user-message').value;

    if (userMessage) {
        displayUserMessage(userMessage);
        document.getElementById('user-message').value = '';

        
        fetch('chatbot.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ message: userMessage })
        })
        .then(response => response.json())
        .then(data => {
            displayBotMessage(data.response);
        })
        .catch(error => console.error('Error:', error));
    }
}

function displayUserMessage(message) {
    const chatHistory = document.getElementById('chat-history');
    const userMessageDiv = document.createElement('div');
    userMessageDiv.classList.add('chat-message', 'user-message');
    userMessageDiv.textContent = message;
    chatHistory.appendChild(userMessageDiv);
    chatHistory.scrollTop = chatHistory.scrollHeight;
}

function displayBotMessage(message) {
    const chatHistory = document.getElementById('chat-history');
    const botMessageDiv = document.createElement('div');
    botMessageDiv.classList.add('chat-message', 'bot-message');
    botMessageDiv.textContent = message;
    chatHistory.appendChild(botMessageDiv);
    chatHistory.scrollTop = chatHistory.scrollHeight;
}



function askForMusicHelp() {
    const chatHistory = document.getElementById('chat-history');
    const botMessageDiv = document.createElement('div');
    botMessageDiv.classList.add('chat-message', 'bot-message');
    botMessageDiv.textContent = `Hi ${username}! 🎶 How can I assist you with your music today? You can ask about available songs, play songs, or see your liked songs.`;
    chatHistory.appendChild(botMessageDiv);
    chatHistory.scrollTop = chatHistory.scrollHeight;

    const userMessageInput = document.getElementById('user-message');
    userMessageInput.placeholder = "Type your message...";
}
