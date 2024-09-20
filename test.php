<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="images/icons8-robot-64.png" type="image/x-icon">
    <title>Chatbot PDF</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f0f5;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            position: relative; /* Ensure the image can be positioned absolutely */
        }
        #chatbox {
            width: 100%;
            max-width: 450px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            animation: fadeIn 0.5s ease-in-out;
        }
        #messages {
            height: 300px;
            overflow-y: auto;
            margin-bottom: 15px;
            padding-right: 10px;
        }
        .message {
            margin-bottom: 15px;
            max-width: 80%;
            word-wrap: break-word;
            display: flex;
            align-items: center;
            animation: slideIn 0.3s ease-in-out;
        }
        .bot-message {
            text-align: left;
            color: #ffffff;
            background-color: #6c63ff;
            padding: 10px;
            border-radius: 10px 10px 10px 0;
            margin-left: 10px;
            flex: 1;
            display: flex;
            align-items: center;
        }
        .bot-message img {
            width: 30px;
            height: 30px;
            margin-right: 10px;
            animation: pulse 1.5s infinite;
        }
        .user-message {
            text-align: right;
            color: #333333;
            background-color: #e0e0e0;
            padding: 10px;
            border-radius: 10px 10px 0 10px;
            align-self: flex-end;
        }
        #userInput {
            width: calc(100% - 55px);
            padding: 10px;
            border: 1px solid #cccccc;
            border-radius: 20px;
            outline: none;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        #userInput:focus {
            border-color: #6c63ff;
        }
        #sendButton {
            padding: 10px 15px;
            background-color: #6c63ff;
            color: #ffffff;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            font-size: 18px;
            margin-left: 10px;
            transition: background-color 0.3s;
        }
        #sendButton:hover {
            background-color: #554bb5;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-30px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.2);
                opacity: 0.8;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-30px);
            }
            60% {
                transform: translateY(-15px);
            }
        }

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-thumb {
            background-color: #cccccc;
            border-radius: 10px;
        }

        #robotImage {
            position: absolute;
            bottom: 20px;
            right: 20px;
            width: 50px;
            height: 50px;
            animation: bounce 2s infinite;
            z-index: 10; /* Assure que l'image du robot est au-dessus */
        }

        #robotBubble {
            position: absolute;
            bottom: 80px; /* Ajuster la position par rapport à l'image du robot */
            right: 80px;  /* Ajuster la position par rapport à l'image du robot */
            background-color: #6c63ff;
            color: #ffffff;
            padding: 10px;
            border-radius: 10px 10px 0px 10px;
            max-width: 200px;
            display: block; /* Caché par défaut */
            z-index: 9; /* Assure que la bulle est sous l'image du robot mais visible */
            animation: fadeInOut 6s infinite;
        }

        @keyframes fadeInOut {
            0% { opacity: 0; }
            25% { opacity: 1; }
            75% { opacity: 1; }
            100% { opacity: 0; }
        }
    </style>
</head>
<body>
    <div id="chatbox">
        <div id="messages"></div>
        <div style="display: flex; align-items: center;">
            <input type="text" id="userInput" placeholder="Écrivez votre réponse...">
            <button id="sendButton">➤</button>
        </div>
    </div>

    <!-- Image animée de robot en bas à droite -->
    <img id="robotImage" src="images/bot-img.png" alt="Robot" />
    <div id="robotBubble">Je suis ici pour vous aider pour la création de votre logo !</div> <!-- Bulle de dialogue -->

    <script>
        let messages = document.getElementById("messages");
        let userInput = document.getElementById("userInput");
        let robotBubble = document.getElementById("robotBubble");

        const botMessages = [
            "Quel est le nom de votre entreprise ou le votre ?",
            "Quelle est votre adresse e-mail ?",
            "Quel est votre numéro de téléphone ?",
            "Quel est votre secteur d'activité ?",
            "Quels sont les messages ou valeurs essentiels que vous souhaitez véhiculer à travers votre logo ?",
            "Quels mots-clés ou adjectifs décrivent le mieux votre entreprise et devraient être reflétés dans le logo ?",
            "Quel est votre style de logo préféré ?",
            "Quelles sont vos couleurs préférées ?",
            "Quelles couleurs souhaitez-vous éviter ?",
            "Y a-t-il des éléments graphiques ou des symboles spécifiques que vous aimeriez intégrer dans le logo ?",
            "Ces éléments ont-ils une signification particulière pour votre entreprise ou votre marque ?",
            "Quels sont des exemples de logos que vous appréciez ?",
            "Quel type de typographie préférez-vous ? Plutôt sobre et élégante, ou plus audacieuse et créative ?",
            "Quel est votre public cible ?",
            "Y a-t-il des éléments spécifiques que le logo devrait prendre en compte pour attirer ce public cible ?",
            "Quelle est l'utilisation prévue du logo ?",
            "Est-ce que vous avez des suggestions pour des éléments visuels qui pourraient représenter votre marque ?",
            "Y a-t-il d'autres informations ou exigences particulières que vous souhaitez partager concernant votre logo ?",
            "Quel est le délai pour ce projet ?",
            "Avez-vous des commentaires ou des précisions à ajouter ?"
        ];

        let step = 0;
        let responses = [];

        function sendMessage(message, type) {
            let messageElement = document.createElement("div");
            messageElement.classList.add("message", type + "-message");

            if (type === "bot") {
                let botIcon = document.createElement("img");
                botIcon.src = "images/icons8-robot-64.png"; // Remplacez cette URL par celle de votre image de robot
                messageElement.appendChild(botIcon);
            }

            let textElement = document.createElement("span");
            textElement.textContent = message;
            messageElement.appendChild(textElement);
            
            messages.appendChild(messageElement);
            messages.scrollTop = messages.scrollHeight;
        }

        function showTypingIndicator() {
            let typingIndicator = document.createElement("div");
            typingIndicator.classList.add("message", "bot-message");
            typingIndicator.innerHTML = '<img src="images/icons8-robot-64.png" /> Le bot est en train d\'écrire...'; // Remplacez l'URL par celle de votre image de robot
            messages.appendChild(typingIndicator);
            messages.scrollTop = messages.scrollHeight;
            return typingIndicator;
        }

        function showRobotBubble(message) {
            robotBubble.textContent = message;
            robotBubble.style.display = 'block';
            setTimeout(() => {
                robotBubble.style.display = 'none';
            }, 3000); // La bulle disparaît après 3 secondes
        }

        document.getElementById("sendButton").addEventListener("click", () => {
            let userMessage = userInput.value.trim();
            if (userMessage !== "") {
                sendMessage(userMessage, "user");
                responses.push(userMessage);
                userInput.value = "";

                if (step < botMessages.length - 1) {
                    let typingIndicator = showTypingIndicator();
                    setTimeout(() => {
                        typingIndicator.remove();
                        sendMessage(botMessages[step], "bot");
                        showRobotBubble(botMessages[step]); // Affiche la bulle du robot
                        step++;
                    }, 1000);
                } else {
                    setTimeout(() => {
                        generatePDF();
                    }, 1000);
                }
            }
        });

        function generatePDF() {
            fetch("generate_pdf.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify(responses),
            })
            .then(response => response.blob())
            .then(blob => {
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement("a");
                a.style.display = "none";
                a.href = url;
                a.download = "reponses.pdf";
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
            });
        }

        sendMessage(botMessages[step], "bot");
        step++;
    </script>
</body>
</html>
