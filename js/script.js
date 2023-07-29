const chatbotToggler = document.querySelector(".chatbot-toggler");
const closeBtn = document.querySelector(".close-btn");
const chatbox = document.querySelector(".chatbox");
const chatInput = document.querySelector(".chat-input textarea");
const sendChatBtn = document.querySelector(".chat-input span");
const inputInitHeight = chatInput.scrollHeight;
const adminNumber = adminData.adminPhoneNumber;
console.log(adminData)



const createChatLi = (message, className) => {
  // Create a chat <li> element with the passed message and className
  const chatLi = document.createElement("li");
  chatLi.classList.add("chat", `${className}`);
  let chatContent =
    className === "outgoing"
      ? `<p></p>`
      : `<span class="material-symbols-outlined">smart_toy</span><p></p>`;
  chatLi.innerHTML = chatContent;
  chatLi.querySelector("p").textContent = message;
  return chatLi; // return chat <li> element
};

const handleChat = () => {
  const userMessage = chatInput.value.trim();
  const phoneNumber = `91${adminNumber}@c.us`;
  
  if (!userMessage) return;

  // Clear the input textarea and set its height to default
  chatInput.value = "";
  chatInput.style.height = `${inputInitHeight}px`;

  // Append the user's message to the chatbox
  chatbox.appendChild(createChatLi(userMessage, "outgoing"));
  chatbox.scrollTo(0, chatbox.scrollHeight);

  setTimeout(() => {
    // Display "Thinking..." message while waiting for the response
    const incomingChatLi = createChatLi("Thinking...", "incoming");
    chatbox.appendChild(incomingChatLi);
    chatbox.scrollTo(0, chatbox.scrollHeight);

    // Make the fetch request to the custom REST API endpoint

    fetch("https://whatgpt.up.railway.app/api/query-train-gpt", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        query: userMessage,
        number: phoneNumber,
      }),
    })
      .then((response) => response.json())
      .then((data) => {
        // Handle the chatbot response
        console.log(data);
        chatbox.removeChild(incomingChatLi); // Remove "Thinking..." message
        chatbox.appendChild(createChatLi(data.response, "incoming"));
        chatbox.scrollTo(0, chatbox.scrollHeight);
      })
      .catch((error) => {
        chatbox.removeChild(incomingChatLi); // Remove "Thinking..." message
        chatbox.appendChild(
          createChatLi("Oops! Something went wrong. Please try again.", "error")
        );
        chatbox.scrollTo(0, chatbox.scrollHeight);
        console.error(error);
      });
  }, 600);
};

chatInput.addEventListener("input", () => {
  // Adjust the height of the input textarea based on its content
  chatInput.style.height = `${inputInitHeight}px`;
  chatInput.style.height = `${chatInput.scrollHeight}px`;
});

chatInput.addEventListener("keydown", (e) => {
  // If Enter key is pressed without Shift key and the window
  // width is greater than 800px, handle the chat
  if (e.key === "Enter" && !e.shiftKey && window.innerWidth > 800) {
    e.preventDefault();
    handleChat();
  }
});

sendChatBtn.addEventListener("click", handleChat);
closeBtn.addEventListener("click", () =>
  document.body.classList.remove("show-chatbot")
);
chatbotToggler.addEventListener("click", () =>
  document.body.classList.toggle("show-chatbot")
);
